
    
    <footer class="footer">
       <span class="text-muted"><em>&copy; <?= date("Y") ?> Alex Aleluia</em></span>
       
       <?php if(logedin()): ?>
       <a href="<?= site_url('User/sugestao') ?>">Seguestão</a>
       <?php endif;  ?>
       
    </footer>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?= PATHHOST . 'assets/js/jquery.min.js' ?>"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/js/ie10-viewport-bug-workaround.js"></script>
    
    <script src="<?= PATHHOST . 'assets/js/script.js?q=21' ?>"></script>
  </body>
</html>