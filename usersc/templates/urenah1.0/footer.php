<?php
require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/container_close.php';
require_once $abs_us_root . $us_url_root . 'users/includes/page_footer.php';

?>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script scr="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function (){
        //Toggles paragraphs with different speeds
        $(".toggle-btn").on('click',function (event){
            var newId = $(this).val();
            var parentId = event.target.id;
            if(newId == parentId){
                $("li.slow"+parentId).toggle("slow");
            }else{
                alert("Nothing to display");
            }
        });
    });
</script>
</body>
<script type="text/javascript">
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});
</script>
<script>
var $hamburger = $(".hamburger");
$hamburger.on("click", function(e) {
  $hamburger.toggleClass("is-active");
});
</script>

<footer id="footer" style="background-color: transparent;">
<p align="center">&copy; <?php echo date("Y"); ?> <?=$settings->copyright; ?></p>
</footer>
<?php require_once($abs_us_root.$us_url_root.'users/includes/html_footer.php');?>
