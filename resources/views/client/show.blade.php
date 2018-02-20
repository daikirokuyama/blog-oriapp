<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>rokuyama.daiki</title>
  <!-- Bootstrap -->
  <link href="/packages/admin/AdminLTE/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset("/lib/highlight/style/solarized-dark.css") }}">
  <link href="/css/style.css" rel="stylesheet">
</head>
<body>

  <div class="main">
    <div class="article">

      <div class="eyecatch-cover">
        <div class="eyecatch">
          <img src="{{ asset("/image/topImages/{$post->top_image}") }}" alt="">
        </div>
      </div>

      <div id="article-header">
        <h1>{{ $post->title }}</h1>
        <div class="date">
          {{ $post->created_at->format('Y.m.d') }} [
          <a href="/">{{ $post->category->category_name }}</a>
          ]
        </div>
      </div>

      {{-- <section id="article-content">{{ $post->content }}</section> --}}
      <section id="article-content"></section>
    </div>
  </div>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="/packages/admin/AdminLTE/bootstrap/js/bootstrap.min.js"></script>
  <script src="{{ asset ("/lib/marked/js/marked.min.js") }}"></script>
  <script src="{{ asset ("/lib/highlight/js/highlight.pack.js") }}"></script>
  <script>
    $(function() {
      marked.setOptions({
        langPrefix: ""
      });

    <?php
      $content = $post->content;
      $order_break = array("\r\n", "\n", "\r");
      $replace_break = '\n';
      $order_quotation = array("\"");
      $replace_quotation = '\"';
      $content = str_replace($order_break, $replace_break, $content);
      $content = str_replace($order_quotation, $replace_quotation, $content);
    ?>

      var html = marked("<?php echo $content ?>");
      $("#article-content").html(html);

      $("#article-content pre code").each(function(i, e) {
        hljs.highlightBlock(e, e.className);
      });
    });
  </script>
</body>
</html>
