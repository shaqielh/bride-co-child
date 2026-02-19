<?php 
/**
* Template Name: Dynamic FAQ Page
* Description: A template for a FAQ page with editable sections and questions
*/

get_header(); 
?>

<div class="faq-container">
  <h2 class="faq-header"><?php echo get_field('faq_main_title') ?: 'Frequently Asked Questions'; ?></h2>
  <p class="faq-subtext">
    <?php echo get_field('faq_subtitle') ?: 'We have listed the most frequently asked questions and topics below to help you feel safe at every stage of your shopping.'; ?>
  </p>
  <p class="faq-contact">
    <?php echo get_field('faq_contact_text') ?: 'For your other questions, you can use the contact form...'; ?>
    <?php if(get_field('contact_page_link')): ?>
      <a href="<?php echo get_field('contact_page_link'); ?>">contact</a>
    <?php else: ?>
      <a href="<?php echo get_permalink(get_page_by_path('contact')); ?>">contact</a>
    <?php endif; ?>
  </p>

  <?php 
  // Check if we have FAQ sections
  if(have_rows('faq_sections')): 
    while(have_rows('faq_sections')): the_row();
      $section_title = get_sub_field('section_title');
      $section_id = sanitize_title($section_title);
  ?>
    <!-- FAQ Section -->
    <h3 class="faq-section-title"><?php echo $section_title; ?></h3>
    <div class="accordion" id="accordion-<?php echo $section_id; ?>">
      <?php 
      // Check if we have FAQ items
      if(have_rows('faq_items')): 
        $item_count = 0;
        while(have_rows('faq_items')): the_row();
          $item_count++;
          $question = get_sub_field('question');
          $answer = get_sub_field('answer');
          $item_id = $section_id . '-' . $item_count;
      ?>
      <div class="accordion-item">
        <h2 class="accordion-header" id="heading-<?php echo $item_id; ?>">
          <button
            class="accordion-button collapsed"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#collapse-<?php echo $item_id; ?>"
            aria-expanded="false"
            aria-controls="collapse-<?php echo $item_id; ?>"
          >
            <i class="bi bi-chevron-right icon"></i> <?php echo $question; ?>
          </button>
        </h2>
        <div
          id="collapse-<?php echo $item_id; ?>"
          class="accordion-collapse collapse"
          aria-labelledby="heading-<?php echo $item_id; ?>"
          data-bs-parent="#accordion-<?php echo $section_id; ?>"
        >
          <div class="accordion-body">
            <?php echo $answer; ?>
          </div>
        </div>
      </div>
      <?php 
        endwhile;
      endif; 
      ?>
    </div>
  <?php 
    endwhile;
  endif; 
  ?>
</div>


<!-- Include Bootstrap and other required scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php get_footer(); ?>