<!DOCTYPE html>
<html lang="en">
<head>
<title>{{ $title }}</title>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<script language="JavaScript" type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
<script src="/js/bootstrap.min.js"></script>

<link rel="stylesheet" href="/css/style.css" />
<link rel="stylesheet" href="/css/paginate.css" />
<link href="/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

<div id="container">

    <div class="span12">
        <ul id="top_menu">

            <li><a href="/">HOME</a></li>

            <li>
                <a href="/media?sort_by=id">MEDIA</a>
                <ul>
                    <li><a href="/media?sort_by=view_time">View Time</a></li>
                    <li><a href="/media?sort_by=views">Views</a></li>
                    <li><a href="/media?sort_by=likes">Likes</a></li>
                    <li><a href="/settings">Settings</a></li>
                </ul>
            </li>

            <li><a href="/tags">TAGS</a></li>
            <li><a href="/playlists">PLAYLISTS</a></li>
            <li class="menu_right"><a href="/add">ADD</a></li>

        </ul>
    </div>

    <div id="content" class="span12">
        {{ $content }}
    </div>

    <div style="clear:both">&nbsp; </div>

    <div id="footer"  class="span12"> Copyright &copy; XYL LTD</div>

</div>

</body>
</html>
