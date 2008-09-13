<?php include('GphpChart.class.php'); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
  <head profile="http://gmpg.org/xfn/11">
    <title>GphpChart : Google Chart API with PHP</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style>
    body { font-family: Calibri, Verdana, sans-serif;}
    ul#nav { list-style: none; margin: 10px; }
    ul#nav li { display: inline; margin-right: 10px;}
    img { float: left; margin: 30px; }
    h1 a small { font-size: 10px; }
    h2 { clear: both; width: 100%; display: block; float: none;     margin-top: 32px; border-top: #EFEFEF 12px dashed; padding-top: 12px; }
    div.google { clear: both; position: relative; height: 400px; margin-bottom: 100px; }
    div.google img { margin: 0; vertical-align: bottom; }
    .code { font-family: Courier; line-height: 1.5em; border: gray 1px solid; background: #DDD; padding: 5px; margin: 5px; display: block; float: none; }
    div#l_G { position: absolute; bottom: 0; left: 75px; }
    div#l_o1 {position: absolute; bottom: 0; left: 300px;}
    div#l_o2 {position: absolute; bottom: 0;  left: 450px;}
    div#l_g {position: absolute; bottom: -50px; left: 600px;}
    div#l_l {position: absolute; bottom: 0; left: 750px; }
    div#l_e {position: absolute; bottom: 0; left: 850px;}
    div.info { margin: 10px; margin-top: 0; background: #EFEFEF; font-size: 14px; padding: 10px; }
    div.info img { float: right; margin:0;}
    div#paypal { position: fixed; top: 10px; right: 10px; background: white; border: black 2px dotted; width: 200px; font-weight: bold; 
    padding: 5px; text-align: center;
    }
    div#paypal a img { display: block; float: left; margin: 5px; padding: 5px; border: #CCC 1px solid;}
    /*div#doc p { display: none;}    div#doc p p { display: block;}*/
    div#doc h4 { color: blue; text-decoration: underline; cursor: hand; }
    </style>
    <script language="JavaScript">
    function toggle(divid)
    {
    theBox = document.getElementById(divid);
    if(theBox.style.display == 'none') theBox.style.display = 'block';
    else theBox.style.display = 'none';
    }
    </script>
  </head>
<body>
<ul id="nav"><li><a href="#why">Usages</a></li><li><a href="#download">Download</a></li><li><a href="#doc">Documentation</a></li><li><a href="#samples">Samples</a></li></ul>

<div class="info">
<div id="paypal">
<!--If you want support, <br />feel free to donate !
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="galeenet@gmail.com">
<input type="hidden" name="item_name" value="Google Chart">

<input type="hidden" name="no_shipping" value="0">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="tax" value="0">
<input type="hidden" name="lc" value="US">
<input type="hidden" name="bn" value="PP-DonationsBF">
<input type="image" src="https://www.paypal.com/fr_FR/i/btn/x-click-but21.gif" border="0" name="submit" alt="Effectuez vos paiements via PayPal : une solution rapide, gratuite et sécurisée">
<br />
<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-butcc-donate.gif" border="0" name="submit" alt="Donate via Paypal">
<br />
<img alt="" border="0" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
</form>

<br />
-->

<a href="http://www.dzone.com/links/gphpchart_google_chart_api_for_php_create_cache.html"><img height="25px" width="101px" src="http://www.dzone.com/links/themes/reader/images/dzlogo-tagline-small.gif"></a>
<a href="http://del.icio.us/post?title=<?php echo urlencode('GphpChart : a PHP class for Google Chart API'); ?>&tags=<?php echo urlencode('php googlechart chart api class'); ?>&url=http://www.malaiac.com/GphpChart/"><img src="http://www.malaiac.net/wp-content/plugins/sociable/images/delicious.png"></a>
<div style="clear: both; display: block; width:100%;">&nbsp;</div>
Contact : 
<br />malaiac // AT // gmail.com 
</div>
<h3>Goog Chart Class</h3>
This page show examples of GphpChart class usages.
<ul>
<li>Thanks to Google for a nice, fast, and easy-to-use API : <a href="http://code.google.com/apis/chart/">Google Chart API</a></li>
<li>There are some limitations to it (who said "labels positions" ? :) ) but it's worth most of the commercial classes anyway.<br /></li>

<li>I am not Google officiel or whatsoever, just a freelance developer and SEO</li>
<li>This class is officially in eternal beta : no garanties (it works, though), no cashback (it's free anyway), no extensive support (but the doc is simple)</li>
<li>I expect Google to update his API in the next few months, so stay tuned</li>
</ul>

<div style="padding: 10px; margin: 10px; background: #FEE; line-height: 1.5em; border: gray 3px dotted; font-size: 1em; float: left; width: 400px; clear: both;">
  <p>
  
  <br /><a href="http://www.dzone.com/links/gphpchart_google_chart_api_for_php_create_cache.html">Vote on Dzone</a>
  <br /><strong>Comments are welcome <a href="http://www.malaiac.com/tools/11-new-gphpchart-class-php-with-google-chart-api.html">on this post</a></strong>
  <br />
  <br />2007-12-18 : first release
  <br />2007-12-24 : <a href="http://orchid.phpxperts.net/2007/12/24/orchid-comes-with-google-chart-api/">Orchid Framework</a> now integrates GphpChart
  </p> 
</div>
<div style="clear: both; width:100%; display: block;">&nbsp;</div>

<h3>Terms of Use</h3>
<ul>
<li>This class is free for you to use, and all Google Chart API terms applies (whatever they are)</li>
<li>A feedback about your usage, and some cool graphs are most welcome</li>
<li>A link back to this page is appreciated, somewhere on your site </li>
<li>The link should be something like :
  <?php echo htmlentities('<a href="http://www.malaiac.com/GphpChart/">Google Chart PHP Class by Malaiac</a>'); ?>
</li>
</ul>
</div>
<div class="info" id="why">
<h3>Why use GphpChart class ?</h3>
<?php
$lc1 = array(10,20,30,40,50,60,70,80,90,100,110,120);
$lc2 = array(83,24,98,02,54,87,98,65,87,54,54,87);
$GphpChart = new GphpChart('lc');
$GphpChart->title = 'My chart';
$GphpChart->filename = 'cache/chart_sample.png';
if(file_exists($GphpChart->filename)) echo $GphpChart->get_Image_String();
else
  {
  $GphpChart->add_data($lc1); $GphpChart->add_data($lc2);    
  echo $GphpChart->get_Image_String();
  $GphpChart->save_Image();
  }
?>
<ul>
<li><strong>Easy to use</strong> within your PHP backend or website</li>
<li><strong>Cache images</strong>: don't break the API limit ( 50 000 calls a day ) by requesting the same image twice.</li>
<li>Example usage:  
<em>http://chart.apis.google.com/chart?cht=lc&chd=t:8.3,16.7,25,33.3,41.7,50,58.3,66.7,75,83.3,91.7,100|69.2,20,81.7,1.7,45,72.5,81.7,54.2,72.5,45,45,72.5&chtt=My+pretty+chart&chs=300x200</em></li>
<li>You set :
<p class="code" style="width: 400px;">
$lc1 = $array;
<br />$GphpChart = new GphpChart("lc");
<br />$GphpChart->title = "My chart";
<br />$GphpChart->add_data($lc1); 
<br />echo $GphpChart->get_Image_String();
</p>
</li>
<li>Which shows the image on the right
</li>
<li>Emulates <strong>95 % of the API functions</strong></li> 
</ul>
</div>

<div class="info" id="download">
<h3>Download GphpChart Class</h3>
<p>Content : 
<ul>
<li>GphpChart.class.php (class file, 12kb)</li>
<li>index.php (this file, 28kb)</li>
</ul> 
<h1><a onclick="javascript:urchinTracker ('/download/tgz/');" href="GphpChart.tgz">Download&nbsp;&nbsp; (tgz)<small>(release 2007.12.18, 11kb)</small></a></h1>
<h1><a onclick="javascript:urchinTracker ('/download/zip/');"href="GphpChart.zip">Download&nbsp;&nbsp; (zip)<small>(release 2007.12.18, 11kb)</small></a></h1>

<h3>Install</h3>
<ul>
<li>Decompress in a GphpChart/ folder on your server</li>
<li>Include the GphpChart.class.php</li>
<li>Call a new graph with : $graph = new GphpChart('type','encoding'); // encoding is text encoding if not specified</li>
</ul>
</div>

<div class="info" id="doc">
<h3>GphpChart documentation</h3>

<ul>
<li><h4><a onclick="toggle('doc_usage');">Basic usage</a></h4>
  <div id="doc_usage" style="display: none;">
  From PHP array to line chart.
  <p class="code">
  <?php
  $data = array('01/12' => 1245,'02/12' => 895, '03/12' => 956, '04/12' => 1356,'05/12' => 1542,'06/12' => 1423);
  $GphpChart = new GphpChart('lc'); // 'lc' stands for a line chart 
  $GphpChart->title = 'Daily Visitors'; // this title will be on the chart image
  $GphpChart->add_data(array_values($data)); // adding values
  $GphpChart->add_labels('x',array_keys($data)); // adding x labels (horizontal axis)
  $GphpChart->add_labels('y',array(0,500,1000,1500)); // adding y labels (left axis)
  echo '<span style="width: 300px; float: right;">'.$GphpChart->get_Image_String().'</span>'; // and showing the image
  ?>
  // daily visitors
  <br />$data = array('01/12' => 1245,'02/12' => 895, '03/12' => 956, '04/12' => 1356,'05/12' => 1542,'06/12' => 1423);
  <br />$GphpChart = new GphpChart('lc'); // 'lc' stands for a line chart 
  <br />$GphpChart->title = 'Daily Visitors'; // this title will be on the chart image
  <br />$GphpChart->add_data(array_values($data)); // adding values
  <br />$GphpChart->add_labels('x',array_keys($data)); // adding x labels (bottom axis)
  <br />$GphpChart->add_labels('y',array(0,500,1000,1500)); // adding y labels (left axis)
  <br />echo $GphpChart->get_Image_String(); 
  <br /><br /><br />
  </p>
  </div>
</li>

<li>
  <h4 onclick="toggle('type_encoding');">Types and encodings</h4>
  <div id="type_encoding" style="display: none;">
  Types are based on <a href="http://code.google.com/apis/chart/#chart_type">API types</a> : "lc","lxy","bhs","bvs","bhg","bvg","p","p3","v","s"
  <br />Encodings are based on <a href="http://code.google.com/apis/chart/#chart_data">API encodings</a> : 's' (simple encoding), 't' (text encoding, default), 'e' (extended encoding)
   
  <p class="code">
$GphpChart = new GphpChart('{type}','{encoding?}');
<br />// if not specified, encoding will be text encoding (float, up to 100) 
  </p>
  According to the encoding, datas are normalized to fit in range.
  </div>
</li>


<li>
  <h4 onclick="toggle('caching');">Image Cache</h4>
  <div id="caching" style="display: none;">
  Charts can be cached on your server. This prevents from calling the same graph again and again, and reaching the API limit ( 50,000 calls a day)
  <p class="code">
<br />$GphpChart = new GphpChart('lc');
  <br />$GphpChart->filename = 'cache/{ a unique filename here}.png';
  <br />AND / OR
  <br />$GphpChart->title = 'my chart'; // filename will 'my+chart.png'
  <br /><br />if(file_exists($GphpChart->filename)) echo $GphpChart->get_Image_String();
  <br />else
  <br />{
  <br />$GphpChart->add_data(/*....*/'); // add data, labels, etc.
  <br />echo $GphpChart->get_Image_String();
  <br />$GphpChart->save_Image(); // mandatory
  <br />}
  </p>
  </div>
</li>


<li>
  <h4 onclick="toggle('add_labels');">Add labels</h4>
  <div id="add_labels" style="display: none;">
  You can add array_keys as labels, but an array may have empty values. 
  <br />If you want to add a full range of (integer) labels to your chart, from 0 to 10 on the bottom axis :
  <p class="code">
  $GphpChart->add_labels('x',array('values_type' => 'discret','min' => 0,'max' => 10));
  </p>
  </div>
</li>

<li>
  <h4 onclick="toggle('fill_area');">Area Fill</h4>
  <div id="fill_area" style="display: none;">
  <p class="code">
$goo_chart->fill('(bg|c)','(s|lg|ls)','&lt;params&gt;')
<br />&nbsp;&nbsp;bg|c = Background or chart area
  <br />&nbsp;&nbsp;s|lg|ls = solid, linear gradient or linear stripes
  <br />&nbsp;&nbsp;Params :
  <br />&nbsp;&nbsp;s : color (RRGGBB format hexadecimal number)
  <br />&nbsp;&nbsp;lg : &lt;angle&gt;,&lt;color 1&gt;,&lt;offset 1&gt;,&lt;color n&gt;,&lt;offset n&gt; 
  <br />&nbsp;&nbsp;ls : &lt;angle&gt;,&lt;color 1&gt;,&lt;width 1&gt;,&lt;color n&gt;,&lt;width n&gt;
  <br />&nbsp;&nbsp;<a href="http://code.google.com/apis/chart/#chart_or_background_fill">http://code.google.com/apis/chart/#chart_or_background_fill</a>
  </p>
  </div>
</li>
  
<li>
  <h4 onclick="toggle('add_style');">Adding Style</h4>
  <div id="add_style" style="display: none;">
  <p class="code">
$goo_chart->add_styles(&lt;string&gt;)
  <br />&nbsp;&nbsp;&lt;data set 1 line thickness&gt;,&lt;length of line segment&gt;,&lt;length of blank segment&gt;
  <br />&nbsp;&nbsp;<a href="http://code.google.com/apis/chart/#line_styles">http://code.google.com/apis/chart/#line_styles</a>
  </p>
  </div>
</li>
  
<li>
  <h4 onclick="toggle('add_grid');">Adding Grid</h4>
  <div id="add_grid" style="display: none;">
  <p class="code">
  $goo_chart->add_grid(&lt;string&gt;)
  <br />&nbsp;&nbsp;&lt;x axis step size&gt;,
  <br />&nbsp;&nbsp;&lt;y axis step size&gt;,
  <br />&nbsp;&nbsp;&lt;length of line segment&gt;,
  <br />&nbsp;&nbsp;&lt;length of blank segment&gt;
  <br />&nbsp;&nbsp;<a href="http://code.google.com/apis/chart/#grid">http://code.google.com/apis/chart/#grid</a>
  </p>
  </div>
</li>
  
 <li>
  <h4 onclick="toggle('add_marker');">Adding Markers</h4>
  <div id="add_marker" style="display: none;">
  <p class="code">
  $goo_chart->add_marker(&lt;string&gt;)
  <br />&nbsp;&nbsp;MARKER = &lt;marker type&gt;,&lt;color&gt;,&lt;data set index&gt;,&lt;data point&gt;,&lt;size&gt;
  <br />&nbsp;&nbsp;&lt;a href="http://code.google.com/apis/chart/#shape_markers"&gt;http://code.google.com/apis/chart/#shape_markers&lt;/a&gt;
  <br />&nbsp;&nbsp;RANGE = &lt;r or R&gt;,&lt;color&gt;,&lt;any value&gt;,&lt;start point&gt;,&lt;end point&gt;
  <br />&nbsp;&nbsp;&lt;a href="http://code.google.com/apis/chart/#hor_line_marker"&gt;http://code.google.com/apis/chart/#hor_line_marker&lt;/a&gt;
  <br />&nbsp;&nbsp;FILL AREA = b,&lt;color&gt;,&lt;start line index&gt;,&lt;end line index&gt;,&lt;any value&gt;
  <br />&nbsp;&nbsp;<a href="http://code.google.com/apis/chart/#fill_area_marker">http://code.google.com/apis/chart/#fill_area_marker</a>
  </p>
  </div>
</li> 

<li>
  <h4 onclick="toggle('not_supported');">Not supported</h4>
  <div id="not_supported" style="display: none;">
  <p class="code">
  LXY charts : 'chd=t:-1|5,33,50,55,7'
  <br />"Provide a single undefined value to evenly space the data points along the x-axis." 
  <br />Not supported. You have to specify the evenly spaced x-axis points.
  </p>
  </div>
</li>

</ul>
</div>

<div id="samples">
<h2>View examples</h2>
<h3>All the graphs below are generated with GphpChart and cached (code included in the download)</h3>


<h2>from <a href="http://code.google.com/apis/chart/">http://code.google.com/apis/chart/</a></h2>
<?php

$months_names = array('J','F','M','A','M','J','J','A','S','O','N','D');
$first = 4; for($i=0;$i<=11;$i++)  {  $months_positions[] = floor($first + ($i * 8.3));  }
foreach($months_positions as $n => $pos) {$months[$pos] = $months_names[$n];}

// LINE CHART 
$lc1 = array(10,20,30,40,50,60,70,80,90,100,110,120);
$lc2 = array(83,24,98,02,54,87,98,65,87,54,54,87);
 
$GphpChart = new GphpChart('lc');
$GphpChart->title = 'Line Chart with markers, fill area and color<br />And some months for x labels';
$GphpChart->filename = 'cache/lc_sample.png';
if(file_exists($GphpChart->filename)) echo $GphpChart->get_Image_String();
else
  {
  $GphpChart->add_data($lc1); 
  $GphpChart->add_style('3,6,3');
  $GphpChart->add_data($lc2);
  $GphpChart->add_style('1,1,0');
  $GphpChart->add_labels('x',$months);
  $GphpChart->add_labels('r',array(0,30,60,90,120));
  $GphpChart->fill('bg','s','efefef');
  $GphpChart->add_marker('c,FF0000,1,1.0,20.0');
  $GphpChart->add_marker('d,80C65A,1,2.0,20.0');
  $GphpChart->add_marker('a,990066,1,3.0,9.0');
  $GphpChart->add_marker('o,FF9900,1,4.0,20.0');
  $GphpChart->add_marker('s,3399CC,1,5.0,10.0');
  $GphpChart->add_marker('v,BBCCED,1,6.0,1.0');
  $GphpChart->add_marker('V,3399CC,1,7.0,1.0');
  $GphpChart->add_marker('x,FFCC33,1,8.0,20.0');
  $GphpChart->add_marker('h,3399CC,1,7.0,1.0');
  $GphpChart->add_marker('B,76A4FB,1,1,0');
  $GphpChart->fill('c','lg','0,76A4FB,1,ffffff,0');
  echo $GphpChart->get_Image_String();
  $GphpChart->save_Image();
  }

// LINE CHART WITH PAIR OF COORDINATES 
$lxy1 = array(0 => 20,30=>30,60=>40,70=>50,90=>60,95=>70,100=>80);
$lxy2 = array(10=>100,30=>90,40=>40,45=>20,52=>10);
$GphpChart = new GphpChart('lxy');
$GphpChart->title = 'LXY chart with range markers';
$GphpChart->filename = 'cache/lxy_sample.png';
if(file_exists($GphpChart->filename)) echo $GphpChart->get_Image_String();
else
  {
  $GphpChart->add_data($lxy1);
  $GphpChart->add_data($lxy2);
  $GphpChart->add_marker('R,ff0000,0,0.1,0.11');
  $GphpChart->add_marker('R,A0BAE9,0,0.75,0.25');
  $GphpChart->add_marker('r,E5ECF9,0,0.75,0.25');
  $GphpChart->add_marker('r,000000,0,0.1,0.11');
  $GphpChart->fill('bg','s','efefef');
  $GphpChart->fill('c','lg','0,76A4FB,1,ffffff,0');
  echo $GphpChart->get_Image_String();
  $GphpChart->save_Image();
  }


// BAR CHARTS 
$bhs1 = array(7,4,11,11,54,87,3,45,87);
$bhs2 = array(22,14,17,11,4,98,3,4,41);
$GphpChart = new GphpChart('bhs','s');
$GphpChart->title = 'Bar Chart Horizontal with stripes';
$GphpChart->filename = 'cache/bhs_sample.png';
if(file_exists($GphpChart->filename)) echo $GphpChart->get_Image_String();
else
  {
  $GphpChart->add_data($bhs1,'ff0000');
  $GphpChart->add_data($bhs2,'00aa00');
  $GphpChart->height= 150;
  $GphpChart->set_bar_width(7,2);
  $GphpChart->fill('bg','ls','0,CCCCCC,0.2,FFFFFF,0.2');
  echo $GphpChart->get_Image_String();
  $GphpChart->save_Image();
  }

$GphpChart = new GphpChart('bvs','s');
$GphpChart->title = 'Bar Chart Vertical with stripes';
$GphpChart->filename = 'cache/bvs_sample.png';
if(file_exists($GphpChart->filename)) echo $GphpChart->get_Image_String();
else
  {
  $GphpChart->width = 500;
  $GphpChart->set_bar_width(30,2);
  $GphpChart->add_data($bhs1,'ff0000');
  $GphpChart->add_data($bhs2,'00aa00');
  $GphpChart->fill('bg','ls','90,999999,0.25,CCCCCC,0.25,FFFFFF,0.25');
  echo $GphpChart->get_Image_String();
  $GphpChart->save_Image();
  }
/*  
// VENN DIAGRAM 
$GphpChart = new GphpChart('v');
$GphpChart->title = 'Venn Diagram with legend';
$GphpChart->filename = 'cache/venn_sample.png';
if(file_exists($GphpChart->filename)) echo $GphpChart->get_Image_String();
else
  {
  $GphpChart->add_data(array(100,80,60));
  $GphpChart->add_data(array(30,30,30));
  $GphpChart->add_data(array(10));
  $GphpChart->add_legend(array('First Set','Second Set','Third Set'));
  echo $GphpChart->get_Image_String();
  $GphpChart->save_Image();
  }
*/

// SCATTER PLOTS 
$s1 = array(61,60,56,44,45,45,47,46,47,36,16,8,1,11,10,13,2,0,8,34); // x coo
$s2 = array(3,4,9,15,32,42,52,46,40,47,53,59,51,48,40,41,16,14,3,18); // y coo
$s3 = array(0,5,11,15,19,23,26,31,37,41,45,49,53,57,61,32,44,3,43,39); // plots sizes
 
$GphpChart = new GphpChart('s','s');
$GphpChart->title = 'Scatter plots with various sizes of plots<br>y axis is API generated, and x axis is class generated to prevent empty values';
$GphpChart->filename = 'cache/scatter_sample.png';
if(file_exists($GphpChart->filename)) echo $GphpChart->get_Image_String();
else
  {
  $GphpChart->width = 700;
  $GphpChart->add_data($s1);
  $GphpChart->add_data($s2);
  $GphpChart->add_data($s3);
  $GphpChart->add_labels('x',array('values_type' => 'discret','min' => 0,'max' => 10));
  // = $GphpChart->add_labels('x',array(0,1,2,3,4,5,6,7,8,9,10));
  // and prevents empty values offsets
  $GphpChart->add_labels('y',array());
  echo $GphpChart->get_Image_String();
  $GphpChart->save_Image();
  }



echo "\n".'<h2>A step further : a stock quotation graph with mobile average</h2>';
echo '<p><strong>Google Chart API current limitation :</strong>
<br />The length of the URL is limited somehow. With these kind of graphs (365 plots of data by data set), you cannot display many data sets
</p>';
 
$GphpChart = new GphpChart('lc','e');
$GphpChart->title = 'Stock graph';
$GphpChart->filename = 'cache/stock_sample.png';
if(file_exists($GphpChart->filename)) echo $GphpChart->get_Image_String();
else
  {
  // creating fake stock values
  $last = 5000;
  $last_move = 100;
  $stock = array();
  for($i=0;$i<=365;$i++)
    {
    $stock[] = $last;
    $mod = rand(0,floor($last / 12));
    if(rand(0,50) > 25) $mod = $mod;
    else $mod = -$mod;  
    $last_move = $mod;
    $last = $last + $mod;
    }
    
  // MM 50
  $mm50 = array_fill(0, count($stock) -1 ,'');
  for($i = 25;$i < (count($stock) - 25); $i ++)
    {
    $stock50 = array_slice($stock,$i-25,50);
    $mm50[$i] = round(array_sum($stock50) / count($stock50),0);
    }
  
  $GphpChart->add_legend(array('Quote','MM50'));
  $GphpChart->add_data($stock,'F4C000');
  $GphpChart->add_data($mm50,'333333');
  $GphpChart->prepare_data(); // needed for markers
  $GphpChart->width = 600;
  $GphpChart->height= 500;
  $GphpChart->add_labels('y',array(0,1000,2000,3000,4000,5000));
  
  // vertical stripes for months
  $GphpChart->fill('c','ls','0,FBFBFB,0.0833,FEFEFE,0.0833');
  
  // months labels
  $GphpChart->add_labels('x',$months);
  
  // monthly grid + vertical grid
  $GphpChart->add_grid('8.3333,10,1,5');
  
  // Quarters labels
  $GphpChart->add_labels('x',array(12 => 'Q1', 37 => 'Q2', 62 => 'Q3', 87 => 'Q4'));
  
  // min line
  $low_value =  min($stock) * $GphpChart->ratio / $GphpChart->range;
  $GphpChart->add_marker("r,FF0000,0,$low_value,".($low_value-0.001));
  
  // max line
  $high_value = max($stock)* $GphpChart->ratio/ $GphpChart->range;
  $GphpChart->add_marker("r,00FF00,0,$high_value,".($high_value+0.001));
  
  // show image
  echo $GphpChart->get_Image_String();
  $GphpChart->save_Image();
  }

?>
<h2>Google in dots</h2>

<div class="google">
<blockquote>"I'm going to hell for that bit. And you're all coming with me! "
<br />Denis O'Leary, <em>No Cure for Cancer</em>
</blockquote>
Strange, isn't it ?

<?php


// G


$GphpChart = new GphpChart('s','t');
$GphpChart->filename = 'cache/letter_G_sample.png';
if(!file_exists($GphpChart->filename)) 
  {
  $dots = array(
  array(20,153,'o',40),
  array(24,113,'o',40),
  array(38,74,'o',40),
  array(63,42,'o',36),
  array(27,190,'o',32),
  array(230,245,'o',28),
  array(41,217,'o',28),
  array(93,22,'o',28),
  array(57,239,'o',20),
  array(117,11,'o',20),
  array(194,264,'o',20),
  array(217,253,'o',20),
  array(75,255,'o',16),
  array(91,263,'o',16),
  array(107,268,'o',16),
  array(139,8,'o',16),
  array(160,7,'o',16),
  array(176,270,'o',16),
  array(122,272,'o',10),
  array(133,273,'o',10),
  array(146,274,'o',10),
  array(159,272,'o',10),
  array(175,7,'o',10),
  array(189,8,'o',10),
  array(203,11,'o',10),
  array(212,12,'o',10),
  array(226,17,'o',10),
  array(242,38,'s',30), 
  array(242,76,'s',30), 
  array(214,90,'s',10),
  array(202,90,'s',10),
  array(189,88,'s',8),
  array(263,92,'s',8),
  array(182,85,'s',4),
  array(175,83,'s',4),
  array(268,94,'s',4),
  array(248,245,'d',12),
  array(240,236,'d',12),
  array(230,230,'d',12),
  array(224,220,'d',12),
  );
  
  unset($xs);unset($ys);unset($markers); unset($sizes);
  foreach($dots as $dot)
    {
    $xs[] = $dot[0];
    $ys[] = $dot[1];
    $markers[] = $dot[2];
    $sizes[] = 2 * $dot[3]; 
    }
  $GphpChart->add_data($xs);
  $GphpChart->add_data($ys);
  //$plots = array_fill(0,count($xs),25);$GphpChart->add_data($plots);
  $GphpChart->add_data($sizes);
  $GphpChart->width = 250;
  $GphpChart->height = 300;
  $color = '33538e';
  foreach($markers as $n => $type){$size = $sizes[$n];$GphpChart->add_marker("$type,$color,0,$n,$size");}
  $GphpChart->save_Image();
  }
  echo '<div id="l_G">'."\n".$GphpChart->get_Image_String().'</div>';


// o o
$dots = array(
array(20,110,'o',32),
array(145,142,'o',32),
array(160,109,'o',40),
array(21,77,'o',40),
array(163,75,'o',32),
array(30,47,'o',32),
array(49,24,'o',28),
array(157,49,'o',22),
array(26,135,'o',22),
array(129,161,'o',22),
array(37,152,'o',16),
array(113,170,'o',16),
array(148,34,'o',16),
array(67,12,'o',16),
array(51,164,'o',12),
array(99,174,'o',12),
array(81,8,'o',12),
array(138,23,'o',12),
array(128,16,'o',12),
array(87,175,'o',12),
array(59,168,'o',10),
array(75,174,'o',10),
array(67,172,'o',10),
array(92,7,'o',10),
array(103,7,'o',10),
array(110,8,'o',10),
array(120,10,'o',10),
);
$GphpChart = new GphpChart('s','t');
$GphpChart->filename = 'cache/letter_o1_sample.png';
if(!file_exists($GphpChart->filename))
  {
  unset($xs);unset($ys);unset($markers); unset($sizes);
  foreach($dots as $dot)  {  $xs[] = $dot[0];  $ys[] = $dot[1];  $markers[] = $dot[2];  $sizes[] = 2.1 * $dot[3];  }
  $GphpChart->add_data($xs);
  $GphpChart->add_data($ys);
  //$plots = array_fill(0,count($xs),25);$GphpChart->add_data($plots);
  $GphpChart->add_data($sizes);
  $GphpChart->width = 180; $GphpChart->height = 220;
  $color = 'be3323';
  foreach($markers as $n => $type){$size = $sizes[$n];$GphpChart->add_marker("$type,$color,0,$n,$size");}
  $GphpChart->prepare_data();
  $GphpChart->save_Image();
  }
  echo '<div id="l_o1">'."\n".$GphpChart->get_Image_String().'</div>';

$GphpChart = new GphpChart('s','t');
$GphpChart->filename = 'cache/letter_o2_sample.png';
if(!file_exists($GphpChart->filename)) 
  {
  unset($xs);unset($ys);unset($markers); unset($sizes);
  foreach($dots as $dot)  {  $xs[] = $dot[0];  $ys[] = $dot[1];  $markers[] = $dot[2];  $sizes[] = 2.1 * $dot[3];  }
  $GphpChart->add_data($xs);
  $GphpChart->add_data($ys);
  //$plots = array_fill(0,count($xs),25);$GphpChart->add_data($plots);
  $GphpChart->add_data($sizes);
  $GphpChart->width = 180; $GphpChart->height = 220;
  $color = 'dade36';
  foreach($markers as $n => $type){$size = $sizes[$n];$GphpChart->add_marker("$type,$color,0,$n,$size");}
  echo '<div id="l_o2">'."\n".$GphpChart->get_Image_String().'</div>';
  $GphpChart->save_Image();
   }
else  echo '<div id="l_o2">'."\n".$GphpChart->get_Image_String().'</div>';
/*
$url = $GphpChart->get_Image_URL();
echo "\n\n<br />url = $url\n\n";
$content = file_get_contents($url);
if(!$content)
  {
  echo "<br />filegetcontest echoue";
  if($fp = fopen($url,'r'))
      {
      $content = fread($fp);
      fclose($fp);
      }
  }
if(!$content) echo "<br />fopen echoue";
else echo $content;


$url    =  
$parts  = parse_url($url); 
$query  = $parts['query']; 

$md5 = md5($query); 
$image = @file_get_contents('cache/'.$md5.'.png'); 

if(!$image) { 
        $image = @file_get_contents("http://chart.apis.google.com/chart?". 
$query); 
        $handle = fopen ('cache/'.$md5.'.png', "w"); 
        fwrite($handle, $image); 
        fclose($handle); 

}
*/
// g 
$dots = array(
array(129,177,'o',14),
array(47,102,'o',14),
array(149,31,'o',14),
array(102,8,'o',14),
array(75,165,'o',14),
array(50,264,'o',14),
array(32,91,'o',20),
array(83,8,'o',20),
array(158,46,'o',20),
array(61,172,'o',20),
array(139,191,'o',20),
array(41,249,'o',20),
array(35,228,'o',28),
array(44,190,'o',28),
array(146,212,'o',28),
array(161,70,'o',28),
array(18,73,'o',28),
array(122,273,'o',28),
array(104,154,'o',28),
array(18,43,'o',34),
array(148,95,'o',34),
array(35,207,'o',34),
array(143,234,'o',34),
array(133,255,'o',34),
array(105,136,'o',34),
array(127,115,'o',34),
array(37,22,'o',28),
array(63,11,'o',20),
array(101,280,'o',34),
array(60,272,'o',10),
array(70,277,'o',10),
array(81,280,'o',10),
array(90,280,'o',10),
array(88,163,'o',10),
array(120,168,'o',10),
array(57,105,'o',10),
array(70,110,'o',10),
array(80,110,'o',10),
array(90,111,'o',10),
array(102,113,'o',10),
array(116,12,'o',10),
array(126,14,'o',10),
array(134,19,'o',10),
array(142,24,'o',10),
array(140,280,'o',14),
array(155,280,'o',14),
array(166,283,'o',8),
array(174,285,'o',4),

);
$GphpChart = new GphpChart('s','t');
$GphpChart->filename = 'cache/letter_g_sample.png';
if(!file_exists($GphpChart->filename)) 
  {
  unset($xs);unset($ys);unset($markers); unset($sizes);
  foreach($dots as $dot)  {  $xs[] = $dot[0];  $ys[] = $dot[1];  $markers[] = $dot[2];  $sizes[] = 2.4 * $dot[3];  }
  $GphpChart->add_data($xs);
  $GphpChart->add_data($ys);
  //$plots = array_fill(0,count($xs),25);$GphpChart->add_data($plots);
  $GphpChart->add_data($sizes);
  $GphpChart->width = 200;
  $GphpChart->height = 250;
  $color = '33538e';
  foreach($markers as $n => $type){$size = $sizes[$n];$GphpChart->add_marker("$type,$color,0,$n,$size");}
  echo '<div id="l_g">'."\n".$GphpChart->get_Image_String().'</div>';
  $GphpChart->save_Image();
  }
else echo '<div id="l_g">'."\n".$GphpChart->get_Image_String().'</div>';


// l
$dots = array(
array(44,253,'o',36),
array(42,221,'o',36),
array(40,190,'o',36),
array(38,156,'o',36),
array(37,124,'o',36),
array(36,88,'o',36),
array(35,57,'o',36),
array(36,28,'o',36),
array(24,9,'o',18),
array(49,8,'o',18),
array(62,5,'o',9),
array(71,4,'o',8),
array(14,3,'o',8),
array(63,265,'o',9),
array(25,263,'o',14),
array(15,260,'o',8),
);
$GphpChart = new GphpChart('s','t');
$GphpChart->filename = 'cache/letter_l_sample.png';
if(!file_exists($GphpChart->filename)) 
  {
  unset($xs);unset($ys);unset($markers); unset($sizes);
  foreach($dots as $dot)  {  $xs[] = $dot[0];  $ys[] = $dot[1];  $markers[] = $dot[2];  $sizes[] = 1.4 * $dot[3];  }
  $GphpChart->add_data($xs);
  $GphpChart->add_data($ys);
  //$plots = array_fill(0,count($xs),25);$GphpChart->add_data($plots);
  $GphpChart->add_data($sizes);
  $GphpChart->width = 200;
  $GphpChart->height = 250;
  $color = '589750';
  foreach($markers as $n => $type){$size = $sizes[$n];$GphpChart->add_marker("$type,$color,0,$n,$size");}
  echo '<div id="l_l">'."\n".$GphpChart->get_Image_String().'</div>';
  $GphpChart->save_Image();
  }
else echo '<div id="l_l">'."\n".$GphpChart->get_Image_String().'</div>';

// e
$dots = array(
array(131,146,'o',36),
array(19,97,'o',36),
array(27,58,'o',36),
array(45,33,'o',36),
array(18,116,'o',28),
array(72,17,'o',28),
array(23,138,'o',22),
array(94,12,'o',22),
array(116,166,'o',22),
array(34,153,'o',18),
array(101,174,'o',18),
array(113,13,'o',18),
array(85,178,'o',12),
array(73,177,'o',12),
array(61,173,'o',12),
array(49,167,'o',12),
array(114,127,'o',12),
array(102,121,'o',12),
array(89,116,'o',12),
array(79,111,'o',12),
array(69,107,'o',12),
array(58,103,'o',12),
array(128,16,'o',12),
array(138,20,'o',8),
array(144,24,'o',6),
array(50,101,'o',6),
);
$GphpChart = new GphpChart('s','t');
$GphpChart->filename = 'cache/letter_e_sample.png';
if(!file_exists($GphpChart->filename)) 
  {
  unset($xs);unset($ys);unset($markers); unset($sizes);
  foreach($dots as $dot)  {  $xs[] = $dot[0];  $ys[] = $dot[1];  $markers[] = $dot[2];  $sizes[] = 1.8 * $dot[3];  }
  $GphpChart->add_data($xs);
  $GphpChart->add_data($ys);
  $GphpChart->add_data($sizes);
  $GphpChart->width = 180; $GphpChart->height = 220;
  $color = 'be3323';
  foreach($markers as $n => $type){$size = $sizes[$n];$GphpChart->add_marker("$type,$color,0,$n,$size");}
  echo '<div id="l_e">'."\n".$GphpChart->get_Image_String().'</div>';
  $GphpChart->save_Image();
  }
else echo '<div id="l_e">'."\n".$GphpChart->get_Image_String().'</div>';
?>
</div>
</div>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-234254-7");
pageTracker._initData();
pageTracker._trackPageview();
</script>
</body></html>
