      <div class="wrap">
        <img src="<?php echo justNow_BASENAME . '/assets/JNadminlogo.png'; ?>" class="justNow_adminlogo">
        <?php settings_errors(); ?>
        <h2>Choose where to use JustNow time</h2>
         <form method="post" action="options.php">
            <?php
               settings_fields("justNow_options");

               do_settings_sections("justNow_options");

               submit_button();
            ?>
         </form>
      </div>
