<script>$('#menu').on('click', function() {
    $('.tap-target').tapTarget('open');
});

</script>


<link href="materialize/css/materialize.min.css" rel="stylesheet"/>
<script src="materialize/js/jquery.min.js"></script>
<script src="materialize/js/materialize.min.js"></script>


<!-- Element Showed -->
<a id="menu" class="waves-effect waves-light btn btn-floating" ><i class="material-icons">menu</i></a>

<!-- Tap Target Structure -->
<div class="tap-target" data-activates="menu">
  <div class="tap-target-content">
    <h5>Title</h5>
    <p>A bunch of text</p>
  </div>
</div>