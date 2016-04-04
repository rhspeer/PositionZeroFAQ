Position Zero FAQ
==================

A Wordpress FAQ plugin optimized for SEO, customer support, & lead generation.

##Roadmap:

  [KanBan Board on Trello](https://trello.com/b/vmM2FZ0X)

##Current Status:
Early version that could be used in production by experienced Wordpress developers, although it is currently just a fairly simplistic custom content type & taxonomy.  There are also a few stubbed in features being actively developed.  My recommendation is to avoid using the master branch and wait until a stable version is indicated. That being said most sites are running with worse code so do as you will. 

##Features:
* FAQ admin interface for adding, editing, & deleting questions & answers just like posts or pages
* Plugin templates can be extended in theme or child theme to customize to any theme
* Question Type taxonomy for grouping questions very similar to how categories work
* List view also known as a archive in WP jargon available at /faq, very similar to a post list
* Question detail view like a post or page 
* A FAQ Menu useful for adding Question Type taxonomy menu items to for filtering large FAQ lists
* Pagination of FAQ lists
* A focus on doing things the Wordpress way and not get to fancy
* Canonical archive URL, WP provides a 2 URL's for Custom Post Types, the less human friendly one, /?post_type=pzfaq, is 301 redirected to the more SEO friendly one, /faq

##Project Goals:
1. Optimize for the best chance of being used in the google answer box
2. Improve customer experience by answering common questions
3. Provide opportunity for site to demonstrate organizations expert level knowlege with an call to action to start a sales funnel
4. Provide keyword rich, useful, and topical content useful for Search Engine Optimization & users alike.
5. Easy to fit into themes using common CSS, HTML, & Wordpress conventions while avoiding complex administration settings. 

##Installation:
1. Download to the plugin directory
2. Activate
3. Add Question Types to organize your questions under FAQ -> Question Types in the left admin menu
4. Add Questions & Answers under FAQ -> Add Question
5. Create a FAQ question type menu under Apperance -> Menu
  1. Add Question Type taxonomy terms to menu
  2. Check "FAQ Menu" under theme locations
  3. Save menu
6. Add /faq as a custom menu item to a publicly available menu so users can navigate to your new FAQ page
7. goto http://your-site.com/faq to review your new FAQ

##How To's:
###Customize the question list & detail pages:
1. If appropriate, and it probably is, create a child theme so you can still upgrade your main them without erasing these changes
2. create a directory called "PositionZeroFAQ_templates"
3. copy archive.php for the question list, and/or single.php from wp-content/plugins/PositionZeroFAQ/includes/templates to the directory you just created
4. hack away
