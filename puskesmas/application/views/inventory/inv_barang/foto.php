<!-- <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>plugins/js/jquery.imgzoom/css/imgzoom.css" />
 <script type="text/javascript" src="<?php echo base_url()?>plugins/js/jquery.imgzoom/scripts/jquery.min.js"></script>
 <script type="text/javascript" src="<?php echo base_url()?>plugins/js/jquery.imgzoom/scripts/jquery.imgzoom.pack.js"></script>
 <a href="<?php echo base_url()?>public/files/foto/10/36.jpg"><img class="thumbnail" src="<?php echo base_url()?>public/files/foto/10/36.jpg" alt="Puppy" widht="100x" height="100px"/></a>
<script>
  $(document).ready(function () {
    $('img.thumbnail').imgZoom();
  });
</script>-->


<!--<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>plugins/js/stylezoom.css" />
<script type="text/javascript">

setTimeout(function() {
  if (location.hash) {
    window.scrollTo(0, 0);
  }
}, 1);


</script>
<div class="holder">
    <div id="image-1" class="image-lightbox">
      <span class="close"><a href="#">X</a></span>
      <img src="http://localhost/projek/epuskesmasgarut/public/files/foto/12/8.jpg" alt="earth!" width="100px" height="100px">
      <a class="expand" href="#image-1"></a>
    </div>
  </div>-->
  <style type="text/css">
.lighter,.lighter *{-webkit-transition:all 0.4s ease-in-out;-moz-transition:all 0.4s ease-in-out;transition:all 0.4s ease-in-out}
.lighter{position:fixed;top:0;bottom:0;left:0;right:0;z-index:160000;opacity:1}
.lighter.fade{opacity:0}
.lighter.fade .lighter-container{-webkit-transform:scale(0.5);-moz-transform:scale(0.5);-ms-transform:scale(0.5);-o-transform:scale(0.5);transform:scale(0.5)}
.lighter img{width:100%;height:100%}
.lighter .lighter-overlay{background:rgba(0,0,0,0.5);height:100%;width:100%}
.lighter .lighter-container{background:white;position:absolute;z-index:160000;top:50%;left:50%;right:50%;bottom:50%;box-shadow:0 2px 8px rgba(0,0,0,0.5)}
.lighter .lighter-container .lighter-close{top: 0;right: 0;width: 30px;height: 30px;margin: -15px;line-height: 30px;font-size: 16pt;font-family: Helvetica,Arial,serif;}
.lighter .lighter-container .lighter-next{display:none;right:40px;top:50%;width:40px;height:40px;margin:-20px 0;line-height:34px;font-size:32pt;font-family:Times,serif}
.lighter .lighter-container .lighter-prev{display:none;left:40px;top:50%;width:40px;height:40px;margin:-20px;line-height:24pt;font-size:34px;font-family:Times,serif}
.lighter .lighter-container .lighter-next,.lighter .lighter-container .lighter-prev,.lighter .lighter-container .lighter-close{cursor:pointer;position:absolute;z-index:320000;text-align:center;border-radius:40px;color:rgba(255,255,255,0.8);background:rgba(0,0,0,0.2);}
.lighter .lighter-container .lighter-next:hover,.lighter .lighter-container .lighter-prev:hover,.lighter .lighter-container .lighter-close:hover{color:white;text-decoration: none;background:rgba(0,0,0,0.6)}
.lighter .lighter-container .lighter-next:active,.lighter .lighter-container .lighter-prev:active,.lighter .lighter-container .lighter-close:active{color:white;background:rgba(0,0,0,0.6)}
  </style>
  <script type="text/javascript">
// Setting Auto Lighter
$(".postingan img").parents("a").on("click",function(a){a.preventDefault();a.stopPropagation();return $(this).lighter()});

// jQuery Lighter Plugin
(function(){var g,h,e,f=function(b,a){return function(){return b.apply(a,arguments)}};g=jQuery;h=(function(){function a(){}a.transitions={webkitTransition:"webkitTransitionEnd",mozTransition:"mozTransitionEnd",oTransition:"oTransitionEnd",transition:"transitionend"};a.transition=function(k){var c,l,d,b;c=k[0];b=this.transitions;for(d in b){l=b[d];if(c.style[d]!=null){return l}}};a.execute=function(d,b){var c;c=this.transition(d);if(c!=null){return d.one(c,b)}else{return b()}};return a})();e=(function(){a.settings={padding:40,dimensions:{width:960,height:540},template:"<div class='lighter fade'>\n  <div class='lighter-container'>\n    <span class='lighter-content'></span>\n    <a class='lighter-close'>&times;</a>\n    <a class='lighter-prev'>&lsaquo;</a>\n    <a class='lighter-next'>&rsaquo;</a>\n  </div>\n  <div class='lighter-overlay'></div>\n</div>"};a.lighter=function(c,d){var b;if(d==null){d={}}b=c.data("_lighter");if(!b){b=new a(c,d);c.data("_lighter",b)}return b};a.prototype.$=function(b){return this.$lighter.find(b)};function a(c,b){if(b==null){b={}}this.show=f(this.show,this);this.hide=f(this.hide,this);this.toggle=f(this.toggle,this);this.keyup=f(this.keyup,this);this.align=f(this.align,this);this.resize=f(this.resize,this);this.process=f(this.process,this);this.href=f(this.href,this);this.type=f(this.type,this);this.image=f(this.image,this);this.prev=f(this.prev,this);this.next=f(this.next,this);this.close=f(this.close,this);this.$=f(this.$,this);this.$el=c;if((this.$el.data("width")!=null)&&(this.$el.data("height")!=null)){if(b.dimensions==null){b.dimensions={width:this.$el.data("width"),height:this.$el.data("height")}}}this.settings=g.extend({},a.settings,b);this.$lighter=g(this.settings.template);this.$overlay=this.$(".lighter-overlay");this.$content=this.$(".lighter-content");this.$container=this.$(".lighter-container");this.$close=this.$(".lighter-close");this.$prev=this.$(".lighter-prev");this.$next=this.$(".lighter-next");this.$body=this.$(".lighter-body");this.width=this.settings.dimensions.width;this.height=this.settings.dimensions.height;this.align();this.process()}a.prototype.close=function(b){if(b!=null){b.preventDefault()}if(b!=null){b.stopPropagation()}return this.hide()};a.prototype.next=function(b){if(b!=null){b.preventDefault()}return b!=null?b.stopPropagation():void 0};a.prototype.prev=function(){if(typeof event!=="undefined"&&event!==null){event.preventDefault()}return typeof event!=="undefined"&&event!==null?event.stopPropagation():void 0};a.prototype.image=function(b){return b.match(/\.(jpeg|jpg|jpe|gif|png|bmp)$/i)};a.prototype.type=function(b){if(b==null){b=this.href()}return this.settings.type||(this.image(b)?"image":void 0)};a.prototype.href=function(){return this.$el.attr("href")};a.prototype.process=function(){var j,c,d,b=this;d=this.type(j=this.href());this.$content.html((function(){switch(d){case"image":return g("<img />").attr({src:j});default:return g(j)}})());switch(d){case"image":c=new Image();c.src=j;return c.onload=function(){return b.resize(c.width,c.height)}}};a.prototype.resize=function(b,c){this.width=b;this.height=c;return this.align()};a.prototype.align=function(){var d,b,c;b=Math.max((d=this.height)/(g(window).height()-this.settings.padding),(c=this.width)/(g(window).width()-this.settings.padding));if(b>1){d=Math.round(d/b)}if(b>1){c=Math.round(c/b)}return this.$container.css({height:d,width:c,margin:"-"+(d/2)+"px -"+(c/2)+"px"})};a.prototype.keyup=function(b){if(b.target.form!=null){return}if(b.which===27){this.close()}if(b.which===37){this.prev()}if(b.which===39){return this.next()}};a.prototype.toggle=function(b){if(b==null){b="on"}g(window)[b]("resize",this.align);g(document)[b]("keyup",this.keyup);this.$overlay[b]("click",this.close);this.$close[b]("click",this.close);this.$next[b]("click",this.next);return this.$prev[b]("click",this.prev)};a.prototype.hide=function(){var d,b,c=this;d=function(){return c.toggle("off")};b=function(){return c.$lighter.remove()};d();this.$lighter.removeClass("fade");this.$lighter.position();this.$lighter.addClass("fade");return h.execute(this.$container,b)};a.prototype.show=function(){var d,b,c=this;b=function(){return c.toggle("on")};d=function(){return g(document.body).append(c.$lighter)};d();this.$lighter.addClass("fade");this.$lighter.position();this.$lighter.removeClass("fade");return h.execute(this.$container,b)};return a})();g.fn.extend({lighter:function(a){if(a==null){a={}}return this.each(function(){var b,c,d;b=g(this);d=g.extend({},g.fn.lighter.defaults,typeof a==="object"&&a);c=typeof a==="string"?a:a.action;if(c==null){c="show"}return e.lighter(b,d)[c]()})}})}).call(this);
  </script>
<table>
<tr>
<?php
   if(isset($data_foto) && !empty($data_foto)){ 
      $i=1;
      foreach ($data_foto as $row ) {
?>
<td>
<div class="postingan">
  <a href="<?php echo base_url()?>public/files/foto/<?php echo $row->id_inventaris_barang; ?>/<?php echo htmlspecialchars($row->namafile); ?>"><img src="<?php echo base_url()?>public/files/foto/<?php echo $row->id_inventaris_barang; ?>/<?php echo htmlspecialchars($row->namafile); ?>" widht="100" height="100"/></a>
  <a href="#" onclick="deleteimg(<?php echo $row->id_inventaris_barang.','."'".$row->namafile."'";?>)">
   <div style="background:#fbbc11;padding:4px;position:relative;bottom:28px;left:4px;cursor:pointer;height:25px;width:25px" id="btndelete__<?php echo $row->id_inventaris_barang.'__'.$row->namafile;?>">
      <i class="glyphicon glyphicon-trash" style="color:#FFFFFF;font-size:17px;position:relative;" title="Hapus Foto"></i>          
   </div>            
   </a>
</div>
</td>
<!--<td>
<div class="img-thumbnail dg-picture-zoom"  style="background-image: url(<?php echo base_url()?>public/files/foto/<?php echo $row->id_inventaris_barang; ?>/<?php echo htmlspecialchars($row->namafile); ?>); background-size: cover; -webkit-transform: scale(1, 1) perspective(10000px) rotateX(0deg); opacity: 1; background-position: 50% 49%; background-repeat: no-repeat no-repeat;width:170px;height:100px">
   <a href="#" onclick="deleteimg(<?php echo $row->id_inventaris_barang.','."'".$row->namafile."'";?>)">
   <div style="background:#fbbc11;padding:4px;position:relative;float:left;margin-right:2px;cursor:pointer;height:25px;width:25px" id="btndelete__<?php echo $row->id_inventaris_barang.'__'.$row->namafile;?>">
      <i class="glyphicon glyphicon-trash" style="color:#FFFFFF;font-size:17px;position:relative;" title="Hapus Foto"></i>          
   </div>            
   </a>
   <div style="background:#fbbc11;padding:4px;position:relative;float:left;margin-right:2px;cursor:pointer;height:25px;width:25px" id="zoom">              
      <i class="glyphicon glyphicon-zoom-in" style="color:#FFFFFF;font-size:17px;position:relative;" title="Zoom In"></i>  
   </div>                    
</div>
<!--<ul class="enlarge">
<li><img src="<?php echo base_url()?>public/files/foto/<?php echo $row->id_inventaris_barang; ?>/<?php echo htmlspecialchars($row->namafile); ?>" width="150px" height="100px" alt="Dechairs" /><span><img src="<?php echo base_url()?>public/files/foto/<?php echo $row->id_inventaris_barang; ?>/<?php echo htmlspecialchars($row->namafile); ?>" alt="Deckchairs" /><br /><?php echo $row->namafile; ?></span></li>
</ul>-->
</td>
      <!--<td><img src="<?php echo base_url()?>public/files/foto/<?php echo $row->id_inventaris_barang; ?>/<?php echo htmlspecialchars($row->namafile); ?> " width="170px" height="100px"/></td>-->

<?php
         if(($i%2)==0){
            echo "</tr><tr>";     
         }
         $i++;
      }
   }
?>
</tr>
</div>
</table>
<!--

<style type="text/css">
  ul.enlarge{
list-style-type:none; /*remove the bullet point*/
margin-left:0;
}
ul.enlarge li{
display:inline-block; /*places the images in a line*/
position: relative;
z-index: 0; /*resets the stack order of the list items - later we'll increase this*/
margin:10px 10px 0 0px;
}
ul.enlarge img{
background-color:#eae9d4;
padding: 6px;
-webkit-box-shadow: 0 0 6px rgba(132, 132, 132, .75);
-moz-box-shadow: 0 0 6px rgba(132, 132, 132, .75);
box-shadow: 0 0 6px rgba(132, 132, 132, .75);
-webkit-border-radius: 4px; 
-moz-border-radius: 4px; 
border-radius: 4px; 
}
ul.enlarge span{
position:absolute;
left: -9999px;
background-color:#eae9d4;
padding: 1px;
font-family: 'Droid Sans', sans-serif;
font-size:.9em;
text-align: center; 
color: #495a62; 
-webkit-box-shadow: 0 0 20px rgba(0,0,0, .75));
-moz-box-shadow: 0 0 20px rgba(0,0,0, .75);
box-shadow: 0 0 20px rgba(0,0,0, .75);
-webkit-border-radius: 8px; 
-moz-border-radius: 8px; 
border-radius:8px;
}
ul.enlarge li:hover{
z-index: 50;
cursor:pointer;
}
ul.enlarge span img{
padding:2px;
background:#ccc;
}
ul.enlarge li:hover span{ 
top: -300px; /*the distance from the bottom of the thumbnail to the top of the popup image*/
left: -20px; /*distance from the left of the thumbnail to the left of the popup image*/
}
ul.enlarge li:hover:nth-child(2) span{
left: -100px; 
}
ul.enlarge li:hover:nth-child(3) span{
left: -200px; 
}
/**IE Hacks - see http://css3pie.com/ for more info on how to use CS3Pie and to download the latest version**/
ul.enlarge img, ul.enlarge span{
behavior: url(pie/PIE.htc); 
}
</style>-->