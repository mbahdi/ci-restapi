<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('rendermsg'))
{
	function rendermsg($array)
	{
		$return = '';
		if (is_array($array)) 
		{
			foreach ($array as $key=>$value) 
			{
				$return .= '<li>'.$value.'</li>';
			}
		}
		return $return;
	}
}

if ( ! function_exists('genSalt'))
{
	function genSalt($len=5)
	{
		$length = $len;
		$salt = '';
		for ($i = 0; $i < $length; $i++)
		{
			$salt .= chr(rand(33, 126));
		}
		return $salt;
	}
}

if ( ! function_exists('genAppId'))
{
	function genUids($len=5)
	{
		return substr ( md5(time() . rand(0, 999)), -$len);
	}
}

if ( !function_exists ( 'unique_url' ))
{
    function unique_url()
    {
	return substr ( md5(time() . rand(0, 999)), 0, 7);
    }
}

if ( ! function_exists('sortlink'))
{
	function sortlink($baseurl,$paging,$sorting,$col)
	{
		$url	= $baseurl.'/'.$paging->page.'/'.$col.'/'.($sorting->select == $col ? ($sorting->sort=='asc' ? 'desc': 'asc') : 'asc').'';
		$class	= ($sorting->select == $col ? ($sorting->sort=='asc' ? "class='desc sort'": "class='asc sort'") : "class='sort'"); 
		$str	= "linkto='".$url."' ".$class."";

		return ($str);
	}
}

if ( ! function_exists('getpaging'))
{
	function getpaging($dt)
	{
		$page		= $dt->page;
		$perpage	= $dt->limit;
		$total		= $dt->total;
		$baseurl	= $dt->baseurl;
		$urls		= $dt->urls;
		$replace	= $dt->number;
		$maxpages	= ceil(intval($total) / intval($perpage));

		$farr			= $urls;
		$farr[$replace]	=	1;
		$larr			= $urls;
		$larr[$replace]	= $maxpages;
		
		if ($maxpages > 10) 
		{
			if($page > 7)
			{
				$pstart = $page - 5	;
				$pend = (($page + 5) > $maxpages ? $maxpages : $page + 5);
					
				if($maxpages < 11)
					$pstart = 1	;
				if($page > $maxpages - 7)
				{
					$end = "";
				} else 
				{
					$end = "...&nbsp;{maxpages}";
				}		
				$first = "&nbsp;{fisrtpages}...&nbsp;";
			} else
			{
				$pstart = 1	;
				$pend = 10	;
				$first = "&nbsp;";
				if($maxpages < 10)
					$end = "";
				else
					$end = "...&nbsp;{maxpages}";
			}		
		} else 
		{
			$pstart = 1	;
			$pend = $maxpages	;
			$first = "&nbsp;";
			$end = "&nbsp;";
		}

		$str = '';
		for($k=$pstart;$k<=$pend;$k++)
		{
			$urls[$replace]	= $k;
			$url = $baseurl.implode('/', $urls);

			if ($k == $page) 
			{
				$str .= "<span>".$k."</span>";
			} else 
			{
				$str .= "<a href='".$url."'>".$k."</a>&nbsp;";
			}		
			$str .= "&nbsp;";
		}
		$str	= '<div class="pagingbox"><div class="pages">Pages: &nbsp;&nbsp;'.$first.$str.$end.'<div class="clear"></div></div><div class="clear"></div></div>';
		$str	= str_replace('{fisrtpages}', "<a href='".$baseurl.implode('/', $farr)."'>1</a>" , $str);
		$str	= str_replace('{maxpages}', "<a href='".$baseurl.implode('/', $larr)."'>".$maxpages."</a>" , $str);

		if ($maxpages == 1 || !$maxpages) $str = '';

		return ($str);		
	}
}

#getpagingYoutube
if ( ! function_exists('getpagingYoutube'))
{
	function getpagingYoutube($dt)
	{
		$page		= $dt->page;
		$perpage	= $dt->limit;
		$total		= $dt->total;
		$baseurl	= $dt->baseurl;
		$urls		= $dt->urls;
		$replace	= $dt->number;
		$maxpages	= ceil(intval($total) / intval($perpage));

		$farr			= $urls;
		//$farr[$replace]	=	1;
		unset($farr[$replace]);
		$larr			= $urls;
		//$larr[$replace]	= $maxpages;
		unset($larr[$replace]);
		
		if ($maxpages > 10) 
		{
			if($page > 7)
			{
				$pstart = $page - 5	;
				$pend = (($page + 5) > $maxpages ? $maxpages : $page + 5);
					
				if($maxpages < 11)
					$pstart = 1	;
				if($page > $maxpages - 7)
				{
					$end = "";
				} else 
				{
					$end = "...&nbsp;{maxpages}";
				}		
				$first = "&nbsp;{fisrtpages}...&nbsp;";
			} else
			{
				$pstart = 1	;
				$pend = 10	;
				$first = "&nbsp;";
				if($maxpages < 10)
					$end = "";
				else
					$end = "...&nbsp;{maxpages}";
			}		
		} else 
		{
			$pstart = 1	;
			$pend = $maxpages	;
			$first = "&nbsp;";
			$end = "&nbsp;";
		}

		$str = '';
		for($k=$pstart;$k<=$pend;$k++)
		{
			//$urls[$replace]	= $k;
			unset($urls[$replace]);
			$url = $baseurl.implode('/', $urls);

			if ($k == $page) 
			{
				$str .= "<span>".$k."</span>";
			} else 
			{
				$str .= "<a href='javascript:void(0);' onClick=\"sendYoutube('".$url."', '".$k."');\">".$k."</a>&nbsp;";
			}		
			$str .= "&nbsp;";
		}
		$str	= '<div class="pagingbox"><div class="pages">Pages: &nbsp;&nbsp;'.$first.$str.$end.'<div class="clear"></div></div><div class="clear"></div></div>';
		$str	= str_replace('{fisrtpages}', "<a href='javascript:void(0);' onClick=\"sendYoutube('".$baseurl.implode('/', $farr)."', '0');\">1</a>" , $str);
		$str	= str_replace('{maxpages}', "<a href='javascript:void(0);' onClick=\"sendYoutube('".$baseurl.implode('/', $larr)."', '".$maxpages."');\">".$maxpages."</a>" , $str);

		if ($maxpages == 1 || !$maxpages) $str = '';

		return ($str);		
	}
}

if ( ! function_exists('celeb2json'))
{
	function celeb2json($dt)
	{
		if(is_array($dt))
		{
			$return = '[';
			$ct = 1;
			foreach($dt as $id => $val)
			{
				$return .= '{"key":"'.$val->id.'","value":"'.stripslashes($val->name).'"}';
				if($ct < count($dt)) $return .= ',';
				$ct++;
			}
			$return .= ']';
		} else 
		{
			$return = '';
		}
		return $return;
	}
}

if ( ! function_exists('celeb2opt'))
{
	function celeb2opt($dt, $select, $label)
	{
		if(is_array($dt))
		{
			$ret = '<select name="'.$label.'" id="'.$label.'">';
			foreach($dt as $id => $val)
			{
				$ret	.= '<option value="'.$val->id.'" '.($val->id == $select ? "class=\"selected\"" : "").'>'.$val->name.'</option>';
			}
			$ret .= '</select>';
		} else 
		{
			$ret = '';
		}
		return $ret;
	}
}

#celebmovies
if ( ! function_exists('celebmovies2json'))
{
	function celebmovies2json($dt)
	{
		if(is_array($dt))
		{
			$return = '[';
			$ct = 1;
			foreach($dt as $id => $val)
			{
				$return .= '{"key":"'.stripslashes($val->name).'","value":"'.stripslashes($val->name).'"}';
				if($ct < count($dt)) $return .= ',';
				$ct++;
			}
			$return .= ']';
		} else 
		{
			$return = '';
		}
		return $return;
	}
}

if ( ! function_exists('celebmovies2opt'))
{
	function celebmovies2opt($dt, $select, $label)
	{
		$select = unserialize(stripslashes($select));
		if(is_array($dt))
		{
			$ret = '<select name="'.$label.'" id="'.$label.'">';
			foreach($dt as $id => $val)
			{
				$ret	.= '<option value="'.$val->name.'" '.(in_array($val->name, $select) ? "class=\"selected\"" : "").'>'.$val->name.'</option>';
			}
			$ret .= '</select>';
		} else 
		{
			$ret = '';
		}
		return $ret;
	}
}

#profession
if ( ! function_exists('prof2json'))
{
	function prof2json($dt)
	{
		if(is_array($dt))
		{
			$return = '[';
			$ct = 1;
			foreach($dt as $id => $val)
			{
				$return .= '{"key":"'.stripslashes($val->name).'","value":"'.stripslashes($val->name).'"}';
				if($ct < count($dt)) $return .= ',';
				$ct++;
			}
			$return .= ']';
		} else 
		{
			$return = '';
		}
		return $return;
	}
}

if ( ! function_exists('prof2opt'))
{
	function prof2opt($dt, $select, $label)
	{
		$select = unserialize(stripslashes($select));
		if(is_array($dt))
		{
			$ret = '<select name="'.$label.'" id="'.$label.'">';
			foreach($dt as $id => $val)
			{
				$ret	.= '<option value="'.$val->name.'" '.(in_array($val->name, $select) ? "class=\"selected\"" : "").'>'.$val->name.'</option>';
			}
			$ret .= '</select>';
		} else 
		{
			$ret = '';
		}
		return $ret;
	}
}

#genre
if ( ! function_exists('genre2json'))
{
	function genre2json($dt)
	{
		if(is_array($dt))
		{
			$return = '[';
			$ct = 1;
			foreach($dt as $id => $val)
			{
				$return .= '{"key":"'.stripslashes($val->name).'","value":"'.stripslashes($val->name).'"}';
				if($ct < count($dt)) $return .= ',';
				$ct++;
			}
			$return .= ']';
		} else 
		{
			$return = '';
		}
		return $return;
	}
}

if ( ! function_exists('genre2opt'))
{
	function genre2opt($dt, $select, $label)
	{
		$select = unserialize(stripslashes($select));
		if(is_array($dt))
		{
			$ret = '<select name="'.$label.'" id="'.$label.'">';
			foreach($dt as $id => $val)
			{
				$ret	.= '<option value="'.$val->name.'" '.(in_array($val->name, $select) ? "class=\"selected\"" : "").'>'.$val->name.'</option>';
			}
			$ret .= '</select>';
		} else 
		{
			$ret = '';
		}
		return $ret;
	}
}

#rating
if ( ! function_exists('rating2json'))
{
	function rating2json($dt)
	{
		if(is_array($dt))
		{
			$return = '[';
			$ct = 1;
			foreach($dt as $id => $val)
			{
				$return .= '{"key":"'.stripslashes($val->code).'","value":"'.stripslashes($val->name).'"}';
				if($ct < count($dt)) $return .= ',';
				$ct++;
			}
			$return .= ']';
		} else 
		{
			$return = '';
		}
		return $return;
	}
}

if ( ! function_exists('rating2opt'))
{
	function rating2opt($dt, $select, $label)
	{
		$select = unserialize(stripslashes($select));
		if(is_array($dt))
		{
			$ret = '<select name="'.$label.'" id="'.$label.'">';
			foreach($dt as $id => $val)
			{
				$ret	.= '<option value="'.$val->code.'" '.(in_array($val->code, $select) ? "class=\"selected\"" : "").'>'.$val->name.'</option>';
			}
			$ret .= '</select>';
		} else 
		{
			$ret = '';
		}
		return $ret;
	}
}

if ( ! function_exists('ratingopt'))
{
	function ratingopt($dt, $select, $label)
	{
		if(is_array($dt))
		{
			$ret = '<select name="'.$label.'" id="'.$label.'" class="inputauto">';
			foreach($dt as $id => $val)
			{
				$ret	.= '<option value="'.$val->code.'" '.($val->code == $select ? "selected" : "").'>'.$val->name.' - '.$val->code.'</option>';
			}
			$ret .= '</select>';
		} else 
		{
			$ret = '';
		}
		return $ret;
	}
}

#movies
if ( ! function_exists('movies2json'))
{
	function movies2json($dt)
	{
		if(is_array($dt))
		{
			$return = '[';
			$ct = 1;
			foreach($dt as $id => $val)
			{
				$return .= '{"key":"'.$val->id.'","value":"'.stripslashes($val->title).'"}';
				if($ct < count($dt)) $return .= ',';
				$ct++;
			}
			$return .= ']';
		} else 
		{
			$return = '';
		}
		return $return;
	}
}

if ( ! function_exists('movies2arr'))
{
	function movies2arr($dt)
	{
		if(is_array($dt))
		{
			$return = array();
			foreach($dt as $id => $val)
			{
				$return[''.$val->id.''] = $val->title;
			}
		} else 
		{
			$return = null;
		}
		return $return;
	}
}

if ( ! function_exists('movies2opt'))
{
	function movies2opt($dt, $select, $label)
	{
		if(is_array($dt))
		{
			$ret = '<select name="'.$label.'" id="'.$label.'">';
			foreach($dt as $id => $val)
			{
				$ret	.= '<option value="'.$val->id.'" '.($val->id == $select ? "class=\"selected\"" : "").'>'.$val->title.'</option>';
			}
			$ret .= '</select>';
		} else 
		{
			$ret = '';
		}
		return $ret;
	}
}

#EMBEDCODE
if ( ! function_exists('vidgetembed'))
{
	function vidgetembed($yid, $ywidth, $yheight, $yauto)
	{
		return ('<object width="'.$ywidth.'" height="'.$yheight.'"><param name="movie" value="https://www.youtube.com/v/'.$yid.'?version=3&autoplay='.$yauto.'"></param><param name="allowScriptAccess" value="always"></param><embed src="https://www.youtube.com/v/'.$yid.'?version=3&autoplay='.$yauto.'" type="application/x-shockwave-flash" allowscriptaccess="always" width="'.$ywidth.'" height="'.$yheight.'"></embed></object>');
	}
}

if ( ! function_exists('writejson'))
{
	function writejson($fname, $data)
	{
		$fp = fopen("".$fname."", "w");
		fwrite($fp, $data);
		fclose($fp);

		return (true);
	}
}

if ( ! function_exists('mainmenus'))
{
	function mainmenus()
	{
		$menus = array(
			'0' => array('url'=>'/','name'=>'Home','alt'=>SITENAME),
			'1' => array('url'=>'/movies/in-theaters/','name'=>'In Theaters','alt'=>'In Theaters'),
			'2' => array('url'=>'/movies/up-coming/','name'=>'Upcoming','alt'=>'Upcoming'),
			'3' => array('url'=>'/photos/','name'=>'Photos','alt'=>'Photos'),
			'4' => array('url'=>'/celebs/','name'=>'Celebs','alt'=>'Celebs'),
			'5' => array('url'=>'/trailers/','name'=>'Trailers','alt'=>'Trailers')
		);

		return ($menus);
	}
}

if ( ! function_exists('get_url'))
{
	function get_url($string)
	{
		$text = str_replace("/","",$string);
		$text = str_replace(":","",$text);
		$text = str_replace(";","",$text);		
		$text = str_replace("'"," ",$text);
		$text = str_replace("&","",$text);
		$text = str_replace("#","",$text);
		$text = str_replace("?","",$text);
		$text = str_replace("!","",$text);		
		$text = str_replace(",","",$text);
		$text = str_replace("*","",$text);
		$text = str_replace("+","",$text);
		$text = str_replace("(","",$text);
		$text = str_replace(")","",$text);
		$text = str_replace("\"","",$text);
		$text = str_replace("[","",$text);
		$text = str_replace("]","",$text);
		$text = str_replace(".","",$text);
		$text = str_replace("{","",$text);
		$text = str_replace("}","",$text);
		$text = str_replace("=","",$text);
		$text = str_replace("^","",$text);
		$text = str_replace("%","",$text);
		$text = str_replace("@","",$text);
		$text = str_replace("`","",$text);
		$text = str_replace("~","",$text);
		$text = str_replace("<","",$text);
		$text = str_replace(">","",$text);
		$text = str_replace("|","",$text);
		$text = preg_replace('/\s\s+/', ' ', $text);
		$text = strtolower($text);
		$text = str_replace(" ","-",$text);
		return $text;
	}
}

if ( ! function_exists('create_url'))
{
	function create_url($prefix,$url)
	{
		$ret = base_url($prefix.'/'.$url.'');

		return ($ret);
	}	
}

if ( ! function_exists('celeb_url'))
{
	function celeb_url($celebs, $limit=0, $hash=0)
	{	
		$ret = array();
		$celebs = unserialize(stripslashes($celebs));
		$ict=0;
		foreach ($celebs as $cid=>$cval) 
		{
			$ict++;
			if ($hash) 
			{
				$ret[] = '<a href="'.create_url('celebs', get_url($cval)).'" alt="'.$cval.'" title="'.$cval.'">#'.$cval.'</a>';
			} else 
			{
				$ret[] = '<a href="'.create_url('celebs', get_url($cval)).'" alt="'.$cval.'" title="'.$cval.'">'.$cval.'</a>';
			}
			if ($limit && ($ict==$limit)) break;
		}
		$ret = implode(', ', $ret);

		return ($ret);
	}	
}

#tags
if ( ! function_exists('tags_url'))
{
	function tags_url($tags, $limit=0, $hash=0)
	{	
		$ret = array();
		$tags = explode(",", $tags);
		$tags = array_filter($tags);
		$strskip = array('-','#','.',',','=',);
		$ict=0;
		foreach ($tags as $cid=>$cval) 
		{
			$ict++;
			$cval = trim($cval);
			if (in_array($cval, $strskip)) 
			{
				continue;
			} else 
			{
				$cval = str_replace(array('#','.','=','@','&','>','<','/','\'',':',';','$','%','^','!','~','`','*','^','(',')','-','+','_'), '', $cval);
				if ($hash) 
				{
					//$ret[] = '<a href="'.create_url('tags', rawurlencode($cval)).'" alt="'.$cval.'" title="'.$cval.'">#'.$cval.'</a>';
					$ret[] = '<a href="'.create_url('tags', get_url($cval)).'" alt="'.$cval.'" title="'.$cval.'">#'.$cval.'</a>';
				} else 
				{
					//$ret[] = '<a href="'.create_url('tags', rawurlencode($cval)).'" alt="'.$cval.'" title="'.$cval.'">'.$cval.'</a>';
					$ret[] = '<a href="'.create_url('tags', get_url($cval)).'" alt="'.$cval.'" title="'.$cval.'">'.$cval.'</a>';
				}
			}			
			if ($limit && ($ict==$limit)) break;
		}
		$ret = implode(', ', $ret);

		return ($ret);
	}	
}

if ( ! function_exists('profession_url'))
{
	function profession_url($prof)
	{	
		$ret = array();
		$prof = unserialize(stripslashes($prof)); 
		foreach ($prof as $cid=>$cval) 
		{
			$ret[] = '<a href="'.create_url('celebs', get_url($cval)).'" alt="'.$cval.'" title="'.$cval.'">'.$cval.'</a>';
		}
		$ret = implode(', ', $ret);

		return ($ret);
	}	
}

if ( ! function_exists('get_thumb'))
{
	function get_thumb($fname)
	{
		$ret = str_replace('files', 'files/.thumbs', $fname);
		return ($ret);
	}	
}

if ( ! function_exists('genre_url'))
{
	function genre_url($genre)
	{	
		$ret = array();
		$genre = unserialize(stripslashes($genre)); 
		foreach ($genre as $gid=>$gval) 
		{
			$ret[] = '<a href="'.create_url('movies/genre', get_url($gval)).'" alt="'.$gval.'" title="'.$gval.'">'.$gval.'</a>';
		}
		$ret = implode(', ', $ret);

		return ($ret);
	}	
}

if ( ! function_exists('get_starsql'))
{
	function get_starsql($stars)
	{	
		$ret = array();
		$stars = unserialize(stripslashes($stars)); 
		foreach ($stars as $sid=>$sval) 
		{
			$ret[] = "url = '".get_url($sval)."'";
		}
		$ret = implode(' or ', $ret);

		return ($ret);
	}	
}

if ( ! function_exists('trendingceleb_list'))
{
	function trendingceleb_list($trendceleb)
	{
		$ret = "<ul class='rceleb'>";
		if (is_array($trendceleb) && count($trendceleb) > 0) 
		{
			$cc = 0;
			foreach ($trendceleb as $cid=>$cval) 
			{
				$cc++;
				$ret .="<li>
					<a href='".create_url('celebs', $cval->url)."' alt='".$cval->name."' title='".$cval->name."'><img src='".$cval->coverpic."'>
					<div class='clear'></div>
					".$cval->name."
					</a>
					<div class='clear'></div>
				</li>";
				if ($cc % 2) 
				{
					$ret .="<li class='gap'>&nbsp;</li>";
				} else 
				{
					$ret .="<li class='gapline'>&nbsp;</li>";
				}
			}
		}
		$ret .="
			<li class='rclear'>
				<a href='".base_url('celebs')."' alt='More Celebs' title='More Celebs' class='butt'>More Celebs</a>
				<div class='clear'></div>
			</li>
		</ul>";

		return ($ret);
	}	
}

if ( ! function_exists('nextpage'))
{
	function nextpage($uritotal=0, $urisegment='', $uristring='', $next=0)
	{
		if (is_numeric($urisegment[$uritotal])) 
		{
			$nextlink = base_url();
			for ($i=1; $i < $uritotal; $i++) 
			{
				if (isset($urisegment[$i])) 
				{
					$nextlink .= ($i == 1 ? $urisegment[$i] : '/'.$urisegment[$i]);
				}			
			}
			$nextlink .= '/'.$next.'';
		} else 
		{
			$nextlink = base_url($uristring."/".$next."");
		}
		return ($nextlink);
	}
}

if ( ! function_exists('gettablist'))
{
	function gettablist($tabdata)
	{
		$ret = '';
		if (is_array($tabdata) && count($tabdata) > 0) 
		{
			$ret .= "<ul class='tablist'>";
			foreach ($tabdata as $key=>$value) 
			{
				extract($value);
				$ret .= "<li class='".$class. ($active ? ' select' : '')."'><a href='".$url."'>".$label."</a></li>";
			}
			$ret .= "</ul>";
		}
		return ($ret);
	}
}

if ( ! function_exists('gettopnavigator'))
{
	function gettopnavigator($videosvar)
	{
		$videosvar	= (object) $videosvar;
		#prev
		if (intval($videosvar->select) > 1) 
		{
			$prevnum	= intval($videosvar->select) - 1;
			$prevlink	= ($prevnum == 1 ? "<a href=\"".$videosvar->url."\">Prev</a>" : "<a href=\"".$videosvar->url."/".$prevnum."\">Prev</a>");
		} else 
		{
			$prevlink	= "Prev";
		}

		#next
		if (intval($videosvar->select) < intval($videosvar->total)) 
		{
			$nextnum	= intval($videosvar->select) + 1;
			$nextlink	= "<a href=\"".$videosvar->url."/".$nextnum."\">Next</a>";
		} else 
		{
			$nextlink	= "Next";
		}

		#info
		$info = "".$videosvar->label." ".$videosvar->select." of ".$videosvar->total."";

		$ret = "<ul class=\"vnav\"><li class=\"left\">".$prevlink."</li><li class=\"center\">".$info."</li><li class=\"right\">".$nextlink."</li></ul>";

		return ($ret);
	}
}

if ( ! function_exists('getgalerythumbs'))
{
	function getgalerythumbs($videosvar, $videos, $display=5)
	{
		//echo('<pre>PHOTOS');print_r($videos);
		$videosvar	= (object) $videosvar;
		$select		= ($videosvar->select - 1);
		$total		= count($videos);
		$middle		= floor($display / 2);
		$perrow		= 5;

		if ($display == 'all' || $display == '' || !$display) 
		{
			$ret = "<ul class='vslide' style='height:auto !important;'>";
			$num = 0;
			foreach ($videos as $key=>$value) 
			{
				$num++;
				$i				= $key;
				$myurl			= ($i == 0 ? "".$videosvar->url."" : "".$videosvar->url."/".($i+1)."");
				$mydt			= $value;
				$mydt->thumb	= (isset($mydt->thumb) ? $mydt->thumb : get_thumb($mydt->photo));
				if ($i == $select) 
				{
					$ret .= "<li class='active'><a href=\"".$myurl."\"><img src=\"".$mydt->thumb."\" border='0'></a></li>";
				} else 
				{
					$ret .= "<li><a href=\"".$myurl."\"><img src=\"".$mydt->thumb."\" border='0'></a></li>";
				}
				if ($num % $perrow) 
				{
					$ret .= "<li class='gap'>&nbsp;</li>";
				} else 
				{
					$ret .= "<li class='gapline'>&nbsp;</li>";
				}
			}
			$ret .= "</ul>";			
		} else 
		{
			if ($total > $display) 
			{
				if ($select == 0) 
				{
					$start	= 0;
					$end	= $display;
				} else 
				{
					$start	= ($select > $middle ? ($select - $middle) : 0);
					$end	= (($start + $display) >= $total ? $total : $start + $display);
				}
			} else 
			{
				$start	= 0;
				$end	= $total;
			}

			$ret = "<ul class='vslide'>";
			for ($i=$start; $i < $end; $i++) 
			{
				$myurl			= ($i == 0 ? "".$videosvar->url."" : "".$videosvar->url."/".($i+1)."");
				$mydt			= $videos[$i];
				$mydt->thumb	= (isset($mydt->thumb) ? $mydt->thumb : get_thumb($mydt->photo));
				if ($i == $select) 
				{
					$ret .= "<li class='active'><a href=\"".$myurl."\"><img src=\"".$mydt->thumb."\" border='0'></a></li>";
				} else 
				{
					$ret .= "<li><a href=\"".$myurl."\"><img src=\"".$mydt->thumb."\" border='0'></a></li>";
				}
				if ($i < $total) 
				{
					$ret .= "<li class='gap'>&nbsp;</li>";
				}
			}
			$ret .= "</ul>";			
		}

		

		return ($ret);
	}
}

if ( ! function_exists('cleantext'))
{
	function cleantext($str)
	{
		$str = str_replace('><', '> <', $str);
		$htmlToText	= new Html2Text (stripslashes(strip_tags(trim($str))), 10000);
		$text		= $htmlToText->convert();	

		#clean txt
		$ctxt= htmlentities($text);
		$ret = str_replace(array("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", "&nbsp;&nbsp;&nbsp;&nbsp;", "&nbsp;&nbsp;&nbsp;", "&nbsp;&nbsp;"), "&nbsp;", $ctxt);
		$ret = str_replace(array("`", "’"), "'", $ret);
		$ret = str_replace(array("``", "’’"), "\"", $ret);
		$ret = str_replace(".", ". ", $ret);

		return ($text);
	}
}

if ( ! function_exists('removern'))
{
	function removern($str)
	{
		$str = str_replace(array('\r','\n'), '', $str);

		return ($str);
	}
}

if ( ! function_exists('stringdotted'))
{
	function stringdotted($dt, $dtarr, $sparated=', ')
	{
		if (preg_match('/./', $dt)) 
		{
			$ret = array();
			$dts = array_unique(array_filter(explode('.', $dt)));

			foreach ($dts as $key=>$value) 
			{
				$ret[] = $dtarr[$value]->name;
			}

			return (implode(''.$sparated.'', $ret));
		}
		return ('');
	}
}

if ( ! function_exists('encodePassword'))
{
	function encodePassword($input)
	{
		$result = "";
		for ($i=0; $i<strlen($input); $i++)
		{
			$result .= str_pad(ord($input[$i]), 3, "0", STR_PAD_LEFT);
		}
		return $result;
	}
}

if ( ! function_exists('decodePassword'))
{
	function decodePassword($input)
	{
		$result = "";
		$input = chunk_split($input, 3, " ");
		$input = explode(" ", $input);
		
		foreach($input as $char)
		{
			if (!empty($char))
				$result .= chr($char);
		}
		return $result;
	}
}

if ( ! function_exists('idtags2Arr'))
{
	function idtags2Arr($idtags, $tagsarr)
	{
		$ret = array();
		$idarr	= array_unique(array_filter(explode('.', $idtags)));
		if (is_array($idarr) && count($idarr) > 0) 
		{
			foreach ($idarr as $key=>$value) 
			{
				$ret[] = $tagsarr[$value]->name;
			}
		}
		return $ret;
	}
}

if ( ! function_exists('idkat2Arr'))
{
	function idkat2Arr($idkats, $katarr)
	{
		$ret = array();
		$idarr	= array_unique(array_filter(explode('.', $idkats)));
		if (is_array($idarr) && count($idarr) > 0) 
		{
			foreach ($idarr as $key=>$value) 
			{
				$ret[] = $katarr[$value]->name;
			}
		}
		return $ret;
	}
}

if(!function_exists('rmdouble'))
{
	function rmdouble($string)
	{
		$ret		= str_replace(array('"', '\''), '', $string);

		return ($ret);
	}
}

if(!function_exists('trimstring'))
{
	//function trimstring($txt, $char, $end_char='&#8230;') 
	function trimstring($txt, $char, $end_char='...') 
	{
		if (strlen($txt) > $char) 
		{
			$str = substr($txt,0,$char);
			$pos = strrpos($str," ");
			if ($pos)
			{
				$str = substr($str,0,$pos) . $end_char ;
			}
		} else
		{
			$str = $txt	;
		}
		return $str;
	}
}

if ( ! function_exists('removedot'))
{
	function removedot($string)
	{
		return (str_replace('.', '', $string));
	}
}

if ( ! function_exists('text_article'))
{
	function text_article($text)
	{
		return (removern(mysql_real_escape_string(stripslashes($text))));
	}
}

?>