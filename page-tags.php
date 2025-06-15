<?php
/**
 * Template Name: Tags Page
 * 
 * Custom page template for displaying all tags in a searchable table
 *
 * @package PlayNeko
 */

get_header();
?>

<main id="primary" class="site-main" data-wp-template-part="main">
    <div class="main-content">
        <header class="page-header">
            <h1 class="page-title"><?php _e('All Tags', 'playneko'); ?></h1>
            <p class="page-description"><?php _e('Browse all available tags', 'playneko'); ?></p>
        </header>

        <div class="taxonomy-table-container">

            <table class="taxonomy-table" id="tags-table">
                <thead>
                    <tr>
                        <th><?php _e('Tag Name', 'playneko'); ?></th>
                        <th><?php _e('Post Count', 'playneko'); ?></th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                        <td colspan="2" class="taxonomy-search-container">
                            <input type="text" id="taxonomy-search" placeholder="<?php _e('Search tags...', 'playneko'); ?>" class="taxonomy-search-input">
                        </td>
                    </tr>
                    <?php
                    $tags = get_tags(array(
                        'hide_empty' => false,
                        'orderby' => 'name',
                        'order' => 'ASC'
                    ));

                    if (!empty($tags) && !is_wp_error($tags)) :
                        foreach ($tags as $tag) :
                    ?>
                        <tr>
                            <td>
                                <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" 
                                   class="taxonomy-link" 
                                   data-wp-interactive="">
                                    <?php echo esc_html($tag->name); ?>
                                </a>
                            </td>
                            <td class="post-count">
                                <?php echo $tag->count; ?>
                            </td>
                        </tr>
                    <?php 
                        endforeach;
                    else :
                    ?>
                        <tr>
                            <td colspan="2" class="no-results">
                                <?php _e('No tags found.', 'playneko'); ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="sidebar-content">
        <?php get_sidebar(); ?>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('taxonomy-search');
    const table = document.getElementById('tags-table');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        let visibleRows = 0;

        rows.forEach(row => {
            const tagName = row.querySelector('.taxonomy-link');
            if (tagName) {
                const text = tagName.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            }
        });

        // Show/hide no results message
        let noResultsRow = tbody.querySelector('.no-search-results-row');
        if (visibleRows === 0 && searchTerm !== '') {
            if (!noResultsRow) {
                noResultsRow = document.createElement('tr');
                noResultsRow.className = 'no-search-results-row';
                noResultsRow.innerHTML = '<td colspan="2" class="no-search-results"><?php _e("No tags match your search.", "playneko"); ?></td>';
                tbody.appendChild(noResultsRow);
            }
            noResultsRow.style.display = '';
        } else if (noResultsRow) {
            noResultsRow.style.display = 'none';
        }
    });
});
</script>

<?php
get_footer(); 