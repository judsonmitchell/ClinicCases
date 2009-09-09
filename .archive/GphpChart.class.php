<?php
class GphpChart 
  {
  var $chart;
  var $chart_url;
  var $base_url = "http://chart.apis.google.com/chart?";
  var $width = 300;
  var $height = 200;
  var $types = array ("lc","lxy","bhs","bvs","bhg","bvg","p","p3","v","s");
  var $chart_types = array('l' => 'line','b' => 'bar','p'=> 'pie','v' => 'venn','s' => 'scatter');
  var $mandatory_parameters = array('chs','chd','cht');
  var $data_prepared = false;
  var $allowed_parameters = array(
  'l' => array('chtt','chdl','chco','chf','chxt','chg','chm','chls','chxp'),
  'b' => array('chtt','chbh','chdl','chco','chf','chxt','chxp'),
  'p' => array('chtt','chco','chf','chl'),
  'v' => array('chtt','chdl','chco','chf'),
  's' => array('chtt','chdl','chco','chf','chxt','chg','chm','chxp'),
  );
  var $range = 1;
  var $encodings = array(
    's' => array('sep' => '','set' => ',','range' => 61,'missing' => '_'),
    't' => array('sep' => ',','set' => '|','range' => 100,'missing' => -1),
    'e' => array('sep' => '','set' => ',','range' => 4096,'missing' => '__'),
    );
  var $simple_encoding = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  // min and max values of horizontal axis
  var $max_xt = 0;
  var $min_xt = 100000; // fake value to be sure we got the min data value
  // min and max values for vertical axis
  var $max_yr = 0;
  var $min_yr = 100000; // fake value to be sure we got the min data value
  var $ratio = false;
  var $cached = true;
  var $prepared = false;
  
  function GphpChart($type = 'lc',$encoding = 't')
  {
  $this->chart = (object) NULL;
  // $chart = new stdClass();
  
  if(!in_array($type,$this->types)) return false;
  else $this->chart->cht = $type;
  $this->chart_type = $this->chart_types[substr($this->chart->cht,0,1)];
  
  if(!in_array($encoding,array_keys($this->encodings))) return false;
  else $this->encoding = $encoding;

  $this->sep = $this->encodings[$this->encoding]['sep'];
  $this->range = $this->encodings[$this->encoding]['range'];
  $this->missing = $this->encodings[$this->encoding]['missing'];
  $this->set = $this->encodings[$this->encoding]['set']; // set separator
  if($this->chart_type == 'venn') $this->set = ',';
  
  
  $string = $this->simple_encoding;
  unset($this->simple_encoding);
  for($i = 0;$i< strlen($string);$i++) $this->simple_encoding[] = $string[$i];
  
  $this->extended_encoding = $this->simple_encoding;
  $this->extended_encoding[] = '-'; $this->extended_encoding[] =   '.'; $this->extended_encoding[] =   '_'; $this->extended_encoding[] =   ',';
  }
  
  
/* PRE GENERATION : add labels, data, axis, etc */  

  function add_data($values,$color = '')
  {
  $this->cached = false;
  if($color != '' && strlen($color) == 6) $this->chart->chco[] = $color;
  $this->datas[] = $values; 
  
  } 
  
  function add_labels($axis,$values)
  {
  $this->cached = false;
  if($values_type = $values['values_type'])
  {
  if($values_type == 'discret')
    {
    $min = $values['min']; $max = $values['max'];
    unset($values);
    for($i = $min; $i<=$max;$i++) $values[] = $i;
    }
  }
  
  // reverse order for Bar Horizontal 
  if($this->chart->cht == 'bhs' && is_string($values[0])) $values = array_combine(array_keys($values),array_reverse(array_values($values)));
  $this->labels[$axis][] = $values;

  if($axis == 'x' || $axis == 't') 
    {
    $this->max_xt = max($this->max_xt,max($values));
    $this->min_xt = min($this->min_xt,min($values));
    }
 
   // min and max values for vertical axis are calculated in prepare_data()  
  }
    
  function set_bar_width($width,$space = 0)
  {
  $this->cached = false;
  $this->chart->chbh = (int) $width;
  if($space != 0) $this->chart->chbh .= ','.$space; 
  }
  function fill($area,$type,$params)
  {
  $this->cached = false;
  $this->chart->chf[] = "$area,$type,$params";
  }
  function add_legend($array)
  {
  $this->cached = false;
  if($this->chart_type == 'pie') $this->chart->chl = implode('|',$array);
  else $this->chart->chdl = implode('|',$array);
  }
  function add_style($string)
  {
  $this->cached = false;
  if($this->chart_type == 'line') $this->chart->chls[] = $string;
  }
  function add_grid($string)
  {
  $this->cached = false;
  if($this->chart_type == 'line' || $this->chart_type == 'scatter') $this->chart->chg[] = $string;
  }
  function add_marker($string)
  {
  $this->cached = false;
  if($this->chart_type == 'line' || $this->chart_type == 'scatter') $this->chart->chm[] = $string;
  }
/* END PRE GENERATION FUNCTIONS */

  



/* GENERATE FUNCTIONS : call prepare functions, prepare url, outputs url or full image string */
  function get_Image_URL()
  {
  if($this->cached) 
    {
    if(!$this->filename) $this->generate_filename();
    return $this->filename;
    }
  else
    {
    if(!$this->prepared) $this->prepare();
    return $this->chart_url;
    }  
  }
  function get_Image_String()
  {
  if($this->cached) 
    {
    if(!$this->filename) $this->generate_filename();
    $string = '<img alt="'.$this->title.'" src="'.$this->filename.'" />';
    }
  else  
    {
    if(!$this->prepared) $this->prepare();
    $string = '<img alt="'.$this->title.'" src="'.$this->chart_url.'" />';
    }
  return $string;
  }

  function prepare()
  {
  if(!$this->data_prepared) $this->prepare_data();
  $this->prepare_labels();
  $this->prepare_title();
  $this->prepare_styles();
  $this->prepare_url();
  $this->prepared = true;
  }
/* END GENERATE FUNCTIONS */  
  

  /* CACHE FUNCTIONS */
  function generate_filename()
  {
  $this->filename = urlencode($this->title).'.png';
  }

  function save_Image()
  {
  if(!$this->filename) $this->generate_filename();
  /* get image file */
  //$this->chart_url = htmlspecialchars($this->chart_url);
  //$this->chart_url = urlencode($this->chart_url);
  
  if(    function_exists('file_get_contents')    && $this->image_content = file_get_contents($this->chart_url)    ) 
    $this->image_fetched = true;
    
  if(!$this->image_fetched)
    {
    if($fp = fopen($this->chart_url,'r'))
      {
      $this->image_content = fread($fp);
      fclose($fp);
      $this->image_fetched = true;
      }
    }
  
  /* write image to cache */
  if($this->image_fetched)
    {
    $fp = fopen($this->filename,'w+');  
    if($fp) 
      {
      fwrite($fp,$this->image_content);
      fclose($fp);
      }
    else { return false; }    
    }
  else { return false; }
  
  return true;
  }
  

/* PREPARE FUNCTIONS : called by generate functions, these ones parse labels and data */  
  function prepare_url()
  {
  $this->chart_url = $this->base_url;
  /*
  foreach($this->mandatory_parameters as $param)
  {
  if(!isset($this->chart->$param)) return false;
  $params[] = $param.'='.$this->chart->$param;
  }
  */
  foreach($this->chart as $k => $v)
    {
    if($v != '') $params[] = "$k=$v";
    }
  $this->chart_url .= implode('&',$params);
  }
  function prepare_styles()
  {
// SIZE
 
  if(($this->width * $this->height) > 300000) 
    {
    
    // reduces dimensions to match API limits ( 300mpixels )
    $size = $this->width * $this->height;
    $this->width = round($this->width * (300000 / $size),0);
    $this->height = round($this->height * (300000 / $size),0);
    }
  $this->chart->chs = $this->width.'x'.$this->height;

// colors
  if(isset($this->chart->chco) && is_array($this->chart->chco)) $this->chart->chco = implode(',',$this->chart->chco);
  if(isset($this->chart->chf) && is_array($this->chart->chf)) $this->chart->chf = implode('|',$this->chart->chf);

// styles
  if($this->chart_type == 'scatter' || $this->chart_type == 'line') 
    {
    if($this->chart_type == 'line') if(isset($this->chart->chls) && count($this->chart->chls)) $this->chart->chls = implode('|',$this->chart->chls);
    if(isset($this->chart->chg) && count($this->chart->chg)) $this->chart->chg = implode('|',$this->chart->chg);
// markers
     if(isset($this->chart->chm) && count($this->chart->chm)) $this->chart->chm = implode('|',$this->chart->chm);
    }

    
  
  }
  
  function prepare_size()
  {
  }
  
  function prepare_data()
  {
  // for lines charts, calculate ratio
  if($this->chart_type == 'line'  || $this->chart_type == 'bar' || $this->chart_type == 'scatter')
    {
    $this->max_yr = 0;
    foreach($this->datas as $n => $data)
      {
      if($this->chart_type == 'scatter' && $n == 2) continue; // ignore min max values for plots sizes
      $this->max_yr = max($this->max_yr,max($data));
      $this->min_yr = min($this->min_yr,min($data));
      }
    $this->ratio = 0.9 * $this->range / $this->max_yr;
    }
   
  foreach($this->datas as $n => $data)
    {
    if($this->chart_type == 'scatter' && $n == 2) $data = $this->encode_data($data,false); // do not normalize plots sizes
    else $data = $this->encode_data($data);
    
    if($this->chart->cht == 'lxy') 
      {
      $this->datas[$n] = implode($this->sep,array_keys($data)).'|'.implode($this->sep,array_values($data));
      }
    else $this->datas[$n] = implode($this->sep,$data);
    }
  
  $this->chart->chd = "$this->encoding:";
  $this->chart->chd .= implode($this->set,$this->datas);
  $this->data_prepared = true;
  }
  
  
  function prepare_labels()
  {
  //chxt= axis titles
  //chxl= set:labels
  //chxr= range
  //chxp= positions
  
  $n = 0;
  if(count($this->labels))
  foreach($this->labels as $axis => $labelles)
    {
    foreach($labelles as $pos => $labels)
      {
      // axis type
      $this->chart->chxt[$n] = $axis;
      if(!count($labels)) continue; // no values = "neither positions nor labels. The Chart API therefore assumes a range of 0 to 100 and spaces the values evenly."
      // axis range
      
      if($this->chart_type == 'line'  || $this->chart_type == 'bar')
      {
      if($axis == 'x' || $axis == 't') 
        { 
        if($this->max_xt) $this->chart->chxr[$n] = $n.','.$this->min_xt.','.$this->max_xt; 
        }
      else  
        {
        if($this->max_yr) $this->chart->chxr[$n] = $n.','.$this->min_yr.','.$this->max_yr;
        }
      }
      
      // axis labels
      $this->chart->chxl[$n] = "$n:|".implode('|',$labels);
      if($this->chart_type == 'line' || $this->chart_type == 'bar' || $this->chart_type == 'scatter')
        {
        if(array_slice(array_keys($labels),0,2) != array(0,1))  $this->chart->chxp[$n] = "$n,".implode(',',array_keys($labels));
        else $this->chart->chxp[$n] = "$n,".implode(',',array_values($labels));
        }
      $n++;         
      }
    }
  if(count($this->chart->chxr)) $this->chart->chxr = implode('|',$this->chart->chxr);
  if(count($this->chart->chxp)) $this->chart->chxp = implode('|',$this->chart->chxp);    
  if(count($this->chart->chxt)) $this->chart->chxt = implode(',',$this->chart->chxt);
  if(count($this->chart->chxl)) $this->chart->chxl = implode('|',$this->chart->chxl);
  }
  function prepare_title()
  {
  //chtt=first+line|second+line
  $this->chart->chtt = str_replace(array("\n","\n\r",'<br />','<br>'),'|',$this->title);
  $this->chart->chtt = str_replace(' ','+',$this->chart->chtt);
  }
  
/* END PREPARE FUNCTIONS */
  
/* ENCODING */
  function encode_data($data,$ratio = true)
  {
  if($this->encoding == 's')
    {
    foreach($data as $n => $value)
      {
      if(empty($value) || $value == '') $data[$n] = $this->missing;
      else $data[$n] = $this->simple_encoding[$value];
      }
    }
  elseif($this->encoding == 't')
    {
    foreach($data as $n => $value)
      {
      
      if(empty($value) || $value == '') $data[$n] = $this->missing; 
      elseif($ratio && $this->ratio) $data[$n] = (float) round($value * $this->ratio,1);
      else $data[$n] = (float) $value;
      }
    }
  elseif($this->encoding == 'e')
    {
    $max = 0; $min = 100000;
    foreach($data as $n => $value)
      {
      if(empty($value) || $value == '') $data[$n] = $this->missing;
      else
        {
        // normalize
        if($ratio && $this->ratio) $value = round($value * $this->ratio,0);
        // encode
        $max = max($max,$value);
        $min = min($min,$value); 
        $value = $this->extended_encode($value);
        $data[$n] = $value;
        }
      }
    }
  return $data;
  }  
  
  function extended_encode($value)
  {
  $first = floor($value / 64);
  $second = $value - ($first * 64);
  $first = $this->extended_encoding[$first];
  $second = $this->extended_encoding[$second];
  return $first.$second;
  }
  
  }

?>
