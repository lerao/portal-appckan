<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html;charset=ISO-8856-1" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />

    <meta http-equiv="X-UA-Compatible" content="IE=100"/>
    <title>Repository Apps CKAN</title>
    <META NAME="Author" CONTENT="Lairson Alencar - Cin/UFPE">

    <META NAME="Title" CONTENT="Repository Apps CKAN">
    <META NAME="Subject" CONTENT="Repository Apps CKAN">
    <META NAME="Description" CONTENT="Repository Apps CKAN">
    <META NAME="Keywords" CONTENT="Repository Apps CKAN">

    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/reset.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/pure-min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/grids-responsive-min.css"/>    
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/forms-min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/buttons-min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/base.css?v1"/>
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/style-apps.css"/>

  </head>
  <body>
    <div class="topBg">
      <a href="https://github.com/lerao/ckanext-appckan" target="_blank"><img style="position: absolute; top: 0; left: 0; border: 0;" src="https://camo.githubusercontent.com/121cd7cbdc3e4855075ea8b558508b91ac463ac2/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f6769746875622f726962626f6e732f666f726b6d655f6c6566745f677265656e5f3030373230302e706e67" alt="Fork me on GitHub" data-canonical-src="https://s3.amazonaws.com/github/ribbons/forkme_left_green_007200.png"></a>    
    </div>
    <div class="wrapper">      

      <!-- Logo -->
      <div class="grid3 first">
        <h1>
          <img src="<?php echo get_template_directory_uri(); ?>/img/respository_apps_ckan_logo.jpg" alt="Repository Apps CKAN">
          <br>
          <span style="font-size: 10px; color:#646473"><em>Sharing applications that use open data.</em></span>
        </h1>
      </div>
      <!-- /Logo -->

      <!-- Header -->
      <div class="grid11 header">
        	
        <div class="tMenu"> 
          <ul>
            <li><a href="/">Home</a></li>
            <li><a href="/all-apps">All Apps</a></li>
            <li><a href="/api-about">API</a></li>
            <li><a href="/app-od">APP-OD</a></li>
            <li><a href="/about">About</a></li>
          </ul>
        </div>

        <div class="tSearch">
          <form name="search_form" action="/search/" method="get" accept-charset="utf-8" id="searchform" role="search" class="search-form">
            <div>
            <span class="screen-reader-text"></span>
              <input type="search" class="search-field" placeholder="Search Apps..." value="" name="search" title="Search Apps...">
              <img src="<?php echo get_template_directory_uri(); ?>/img/search-form.png" onclick="javascript: document.search_form.submit()" style="cursor: pointer">
              <span class="count-apps-text">
                <div class="tit">
                  <?php echo(getCountApps()); ?>
                </div>
                <div class="sub">
                  Apps
                </div>
              </span>
            </div>
          </form>
        </div>

      </div>
      <!-- /header -->

      <!-- Conteudo -->
      <div class="meio first">

        <div class="grid14 conteudo first">
