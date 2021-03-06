<?php

/**
* Save Page Builder Data When Saving Page
*/
function depb_pbbase_save_post($post_id, $post)
{

   /* Stripslashes Submitted Data */
    $request = stripslashes_deep( $_POST );

   /* Verify/validate */
    if (! isset( $request['depb_nonce'] ) || ! wp_verify_nonce( $request['depb_nonce'], 'depb_nonce_action' )) {
        return $post_id;
    }
   /* Do not save on autosave */
    if (defined('DOING_AUTOSAVE' ) && DOING_AUTOSAVE) {
        return $post_id;
    }
   /* Check post type and user caps. */
    $post_type = get_post_type_object( $post->post_type );
    if ('page' != $post->post_type || !current_user_can( $post_type->cap->edit_post, $post_id )) {
        return $post_id;
    }

    save_or_update($post_id, "_depb", $request);
    save_or_update($post_id, "use_depagebuilder", $request);

    $post_content = generate_content($request);

    /* Post Data To Save */
    $this_post = array(
        'ID'           => $post_id,
        'post_content' => sanitize_post_field( 'post_content', $post_content, $post_id, 'db' ),
    );
    
    /**
     * Prevent infinite loop.
     * @link https://developer.wordpress.org/reference/functions/wp_update_post/
     */
    remove_action( 'save_post', 'depb_pbbase_save_post' );
    wp_update_post( $this_post );
    add_action( 'save_post', 'depb_pbbase_save_post' );
}

/**
 * Updates post meta data
 */
function save_or_update($post_id, $meta_key, $request)
{
    /* Get (old) saved page builder data */
    $saved_data = get_post_meta( $post_id, $meta_key, true );
    /* Get new submitted data and sanitize it. */
    $submitted_data = isset( $request[$meta_key] ) ? $request[$meta_key] : null;
    /* New data submitted, No previous data, create it  */
    if ($submitted_data && '' == $saved_data) {
        add_post_meta( $post_id, $meta_key, $submitted_data, true );
    } /* New data submitted, but it's different data than previously stored data, update it */
    elseif ($submitted_data && ( $submitted_data != $saved_data )) {
        update_post_meta( $post_id, $meta_key, $submitted_data );
    } /* New data submitted is empty, but there's old data available, delete it. */
    elseif (empty( $submitted_data ) && $saved_data) {
        delete_post_meta( $post_id, $meta_key );
    }
}

/**
 * Generates post body content
 */
function generate_content($request) {
    $submitted_data = isset( $request["_depb"] ) ? $request["_depb"] : null;
    $generated = "";

    foreach($submitted_data as $row) {
        echo $row["type"];
        ob_start();
        switch ($row["type"]) {
            case "dpb-hero":
                ?>
                <div class="hero-content" style="background-image: url(<?=$row["bg"]?>)">
                    <div class="hero-text-container">
                        <div class="hero-text-content" style="text-align: <?=$row["align"]?>; color: <?=$row["fontColor"]?>"><?=$row["text"]?></div>
                    </div>
                </div>
                <?php 
                break;
            case "dpb-slideshow":
                $pictures = json_decode($row["pictures"]);
                ?>
                <div class="slideshow">
                    <div class="slideshow-content" style="width: <?= count($pictures) * 100 ?>%">
                    <?php
                    foreach($pictures as $pic) {
                        ?>
                            <div class="slideshow-slide" style="background-image: url(<?=$pic->full->url ?>); width: <?= (100 / count($pictures)) ?>%">
                                <div class="slideshow-slide-content <?= isset($pic->align) ? $pic->align : "left" ?>" style="color: <?=$pic->fontColor ?>">
                                    <div class="slideshow-slide-content-text"><?=isset($pic->text) ? $pic->text : "" ?></div>
                                    <?php if (isset($pic->link)) { ?>
                                    <a class="btn" href="<?=$pic->link ?>" style="border-color: <?=isset($pic->fontColor) ? $pic->fontColor : "" ?>"><?=$pic->linkLabel ?></a>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php
                    }
                    ?>
                    </div>
                    <div class="slideshow-interface">
                        <i class="fa fa-chevron-left slideshow-left" aria-hidden="true"></i>
                        <i class="fa fa-chevron-right slideshow-right" aria-hidden="true"></i>
                    </div>
                </div>
                <?php
                break;
        }
        $generated .= ob_get_clean();
    }

    return $generated;
}