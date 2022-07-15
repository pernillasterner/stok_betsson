<?php
namespace Lib\Classes;

class GeneralHelper {

    /**
     * Gets the term id value to be pass as $post_id 
     * parameter to ACF. Will only return a value if
     * current page is a category page.
     *
     * @return string 
     */
    public function get_acf_term_id() {
        if( !is_tax() && !is_category() ) {
            return null;
        }

        return get_queried_object()->taxonomy . '_' . get_queried_object()->term_id;
    }

    /**
     * Gets the image alt text.
     * Default: Site title
     *
     * @return string
     */
    public function get_image_alt( $imageID ) {
        $alt = get_post_meta( $imageID, '_wp_attachment_image_alt', true);

        return ( !$alt ) ? get_bloginfo( 'name' ) : $alt;
    }


    /**
     * Gets the current page's sub / sibling pages
     *
     * @return object 
     */
    public function get_sub_navigation( $parent = 0 ) {
        global $post;

        $items = null;
        $postType = $post->post_type;
        $parent = null;

        if( is_singular( 'job' ) ) {
            $jobsPageId = get_field( 'jobs_page', 'default-pages' );

            if( $jobsPageId && $parentOfJobsPage = get_post_ancestors( $jobsPageId ) ) {
                $postType = 'page';
                $parent = $parentOfJobsPage[0];
            }         

        } elseif( is_singular( 'post' ) ) {
            $postType = 'page';
            $categories = get_the_category();

            if( $categories ) {
                $firstCategory = $categories[0];
                $pageId = get_field( $firstCategory->slug. '_page', 'default-pages' );
                $parentOfPage = get_post_ancestors( $pageId );
                $parent = $parentOfPage ? $parentOfPage[0] : null;
            }

        } else {     
            $pageAncestors = get_post_ancestors( $post );

            if( $post->post_parent === 0 ) {
                $parent = $post->ID;
            } elseif( count( $pageAncestors ) > 1 ) {
                $parent = end( $pageAncestors );
            } else {
                $parent = $post->post_parent;
            }
        }

        if( $parent && $postType ) {
            $items = get_pages( array( 
                'post_type' => $postType,         
                'parent' => $parent,
                'sort_column' => 'menu_order',
            ) ); 
        }

        return $items;
    }

    public function get_post_categories() {
        global $post;

        if( in_array( get_post_type(), array( 'faq', 'instagram' ) ) ) {
            $taxonomy = get_post_type() === 'instagram' ? 'hashtag' : 'faq-category';
            $categories = get_the_terms( $post, $taxonomy );
            $bgColorClass = 'is-secondary';
        } else {
            $categories = get_the_category();
        }

        return $categories;       
    }

}

new GeneralHelper();