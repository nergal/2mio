<!--  Google analytics only for /<?=$this->sec?>/*** -->

<script type='text/javascript'> 
var _gaq = _gaq || [];

_gaq.push(['_setAccount', '<?=$this->code?>']);
_gaq.push(['_setDomainName', '<?=$this->domain?>']);
_gaq.push(['_setAllowLinker', true]);
_gaq.push(['_setAllowHash', false]);
_gaq.push(['_trackPageview']);

_gaq.push(['t2._setAccount', 'UA-25600556-1']);
_gaq.push(['t2._setDomainName', '<?=$this->domain?>']);
_gaq.push(['t2._setAllowLinker', true]);
_gaq.push(['t2._setAllowHash', false]);
_gaq.push(['t2._trackPageview']);

(function() {
var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
</script>
