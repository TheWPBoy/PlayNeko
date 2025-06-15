<?php
/**
 * Template Name: Categories Page
 * 
 * Custom page template for displaying all categories in a searchable table
 *
 * @package PlayNeko
 */

get_header();
?>

<main id="primary" class="site-main" data-wp-template-part="main">
    <div class="main-content">
        <header class="page-header">
            <h1 class="page-title"><?php _e('All Categories', 'playneko'); ?></h1>
            <p class="page-description"><?php _e('Browse all available categories', 'playneko'); ?></p>
        </header>

        <div class="taxonomy-table-container">

            <table class="taxonomy-table" id="categories-table">
                <thead>
                    <tr>
                        <th><?php _e('Category Name', 'playneko'); ?></th>
                        <th><?php _e('Post Count', 'playneko'); ?></th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                        <td colspan="2" class="taxonomy-search-container">
                            <input type="text" id="taxonomy-search" placeholder="<?php _e('Search Categories...', 'playneko'); ?>" class="taxonomy-search-input">
                        </td>
                    </tr>
                    <?php
                    $categories = get_categories(array(
                        'hide_empty' => false,
                        'orderby' => 'name',
                        'order' => 'ASC'
                    ));

                    if (!empty($categories) && !is_wp_error($categories)) :
                        foreach ($categories as $category) :
                    ?>
                        <tr>
                            <td>
                                <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" 
                                   class="taxonomy-link" 
                                   data-wp-interactive="">
                                    <?php echo esc_html($category->name); ?>
                                </a>
                            </td>
                            <td class="post-count">
                                <?php echo $category->count; ?>
                            </td>
                        </tr>
                    <?php 
                        endforeach;
                    else :
                    ?>
                        <tr>
                            <td colspan="2" class="no-results">
                                <?php _e('No categories found.', 'playneko'); ?>
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
    const table = document.getElementById('categories-table');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        let visibleRows = 0;

        rows.forEach(row => {
            const categoryName = row.querySelector('.taxonomy-link');
            if (categoryName) {
                const text = categoryName.textContent.toLowerCase();
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
                noResultsRow.innerHTML = '<td colspan="2" class="no-search-results"><?php _e("No categories match your search.", "playneko"); ?></td>';
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