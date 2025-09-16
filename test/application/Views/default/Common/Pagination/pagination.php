<div class="flex justify-center w-full">
    <ul class="pages-items flex flex-wrap gap-2 items-center">
        <?php if ($current_page > 1): ?>
            <li class="items">
                <a href="<?= $current_page == 2 ? $base_url . (!empty($query_params) ? '?' . $query_params : '') : $prev_page_url; ?>"
                    class="px-4 h-10 flex items-center justify-center rounded-lg border border-gray-700 text-gray-300 hover:bg-white hover:text-red-700 transition-colors gap-1">
                    Prev
                </a>
            </li>
        <?php else: ?>
            <li>
                <span class="px-4 h-10 flex items-center justify-center rounded-lg border border-gray-700 text-gray-300 hover:bg-white hover:text-red-700 transition-colors gap-1">First</span>
            </li>
        <?php endif; ?>
        <li class="items current">
            <span class="btn-primary w-10 h-10 flex items-center justify-center rounded-lg text-white font-medium">
                <?= $current_page; ?>
            </span>
        </li>
        <?php if ($is_next): ?>
            <li class="items">
                <a href="<?= $next_page_url; ?>" class="px-4 h-10 flex items-center justify-center rounded-lg border border-gray-700 text-gray-300 hover:bg-white hover:text-red-700 transition-colors gap-1">
                    Next
                </a>
            </li>
        <?php else: ?>
            <li class="items"><span class="px-4 h-10 flex items-center justify-center rounded-lg border border-gray-700 text-gray-300 hover:bg-white hover:text-red-700 transition-colors gap-1">End</span>
            </li>
        <?php endif; ?>
    </ul>
</div>
