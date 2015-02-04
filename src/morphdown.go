package main

import (
	"fmt"
	"log"
	"os"
	"os/exec"
	"os/user"
	"path"
	"runtime"
)

var port string = "8010"

type ExitEvent int

const (
	BrowserClosed ExitEvent = iota
	ServerStopped
)

func CreateServer(port string) *exec.Cmd {
	_, this_morphdown, _, _ := runtime.Caller(0)
	morphdown_dir := path.Dir(this_morphdown)
	public_dir := path.Clean(path.Join(morphdown_dir, "..", "web"))

	server := exec.Command(
		"php",
		"-S", "127.0.0.1:"+port,
		"-t", public_dir,
		path.Join(morphdown_dir, "..", "src", "router.php"),
	)

	server.Env = os.Environ()

	server.Stdin = os.Stdin
	server.Stdout = os.Stdout
	server.Stderr = os.Stderr

	return server
}

func CreateBrowser(port string) *exec.Cmd {
	me, err := user.Current()
	if err != nil {
		fmt.Println("There's no current user'")
		os.Exit(1) // error
	}

	browser := exec.Command(
		"google-chrome",
		"--app=http://127.0.0.1:"+port,
		"--disk-cache-size", "0",
		"--no-proxy-server",
		fmt.Sprintf("--user-data-dir=%s/.morphdown", me.HomeDir),
	)

	return browser
}

func GetFilename(args []string) string {
	if len(args) < 2 {
		fmt.Println("Filename required")
		fmt.Println("Usage: morphdown [filename]")
		os.Exit(1) // error
	}

	filename := args[1]
	stat, err := os.Stat(filename)

	if os.IsNotExist(err) {
		fmt.Printf("No such file or directory: %s", filename)
		os.Exit(1) // error
	}

	if stat.IsDir() {
		fmt.Printf("Cannot open directory: %s", filename)
		os.Exit(1) // error
	}

	return filename
}

func main() {
	filename := GetFilename(os.Args)

	fmt.Println("Starting Morphdown server")

	server := CreateServer(port)
	server.Env = append(server.Env, fmt.Sprintf("MORPHDOWN_FILE=%s", filename))
	server.Start()

	fmt.Println("Starting browser")

	browser := CreateBrowser(port)
	browser.Start()

	quit := make(chan ExitEvent)

	go func() {
		browser.Wait()
		fmt.Println("Browser has closed")
		quit <- BrowserClosed
	}()
	go func() {
		server.Wait()
		fmt.Println("Server has stopped")
		quit <- ServerStopped
	}()

	stopped := <-quit
	if stopped == BrowserClosed {
		if err := server.Process.Kill(); err != nil {
			log.Fatal("Failed to stop server")
		}
		<-quit // wait for server
	} else {
		if err := browser.Process.Kill(); err != nil {
			log.Fatal("Failed to close browser")
		}
		<-quit // wait for browser
	}

	fmt.Println("Morphdown is down")

	return
}
