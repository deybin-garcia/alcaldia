<?php
function empty_state($icon, $title, $subtitle=''){
  echo '<div class="text-center text-muted py-5">';
  echo '<i class="bi ' . htmlspecialchars($icon) . ' display-6 d-block mb-2"></i>';
  echo '<h5 class="mb-1">' . htmlspecialchars($title) . '</h5>';
  if ($subtitle) echo '<div class="small">' . htmlspecialchars($subtitle) . '</div>';
  echo '</div>';
}
?>