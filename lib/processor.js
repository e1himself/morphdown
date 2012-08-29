var async = require('async')
  , marked    = require('marked')

  , pygmentize = require('./pygmentize')

  , codeCache = {}
  , codeBlock = 0

function Processor (source) {
  this.source = source
  this.blocks = []
  marked.setOptions({
      gfm: true
    , pedantic: false
    , sanitize: true
    , highlight: this.highlight.bind(this)
  })
}

Processor.prototype.highlight = function(code, lang) {
  this.blocks.push({ code: code, lang: lang })
  return '<CODEBLOCK id="' + this.blocks.length + '"/>'
}

Processor.prototype.process = function (callback) {
  var html         = marked(this.source + '\n')
    , newCodeCache = {}
  async.map(
      this.blocks
    , function (block, callback) {
        var key = block.lang + block.code
        if (codeCache[key]) {
          return callback(null, newCodeCache[key] = codeCache[key])
        }
        pygmentize(block.lang, block.code, function (err, html) {
          if (err) return callback(err)
          callback(null, newCodeCache[key] = html)
        })
      }
    , function (err, blocks) {
        if (err) return callback(err)
        blocks.forEach(function (code, i) {
          var re = new RegExp('<pre><code class="[^"]*"><CODEBLOCK id="' + (i + 1) + '"/></code></pre>')
          html = html.replace(re, code)
        })
        codeCache = newCodeCache
        callback(null, html)
      }
  )
  return this
}

module.exports = Processor