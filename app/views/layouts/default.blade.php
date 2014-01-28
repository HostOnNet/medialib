<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{{ $title }}</title>

<script language="JavaScript" type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
<link rel="stylesheet" href="/css/style.css" />
<link rel="stylesheet" href="/css/paginate.css" />

</head>
<body>

<div id="page">

    <div id="header">
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

    <div id="content">
        {{ $content }}
    </div>

    <div style="clear:both">&nbsp; </div>

    <div id="footer"> Copyright &copy; XYL LTD</div>

</div>

</body>
</html>
