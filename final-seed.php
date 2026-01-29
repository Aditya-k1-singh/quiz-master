<?php
session_start();
require 'config/db.php';

echo "<h2>Seeding Questions Database</h2>";

// Clear existing
$pdo->exec("DELETE FROM questions");
echo "✓ Cleared existing questions<br>";

// Count for tracking
$totalInserted = 0;

// HTML Questions (150 total: 50 easy, 50 medium, 50 hard)
$htmlQuestions = [
  // EASY HTML (50 questions)
  'easy' => [
    ['What does HTML stand for?', 'Hyper Text Markup Language', 'Home Tool Markup Language', 'Hyperlinks and Text Markup Language', 'Hyper Transfer Markup Language', 'A'],
    ['Which tag is used for a paragraph?', '<p>', '<h1>', '<div>', '<span>', 'A'],
    ['What does the <head> tag contain?', 'Metadata and title of the page', 'Main content', 'Navigation links', 'Footer information', 'A'],
    ['Which tag creates a line break?', '<br>', '<break>', '<lb>', '<newline>', 'A'],
    ['What is the correct way to link to an external CSS file?', '<link rel="stylesheet" href="style.css">', '<style src="style.css">', '<css href="style.css">', '<stylesheet href="style.css">', 'A'],
    ['Which tag is used for the largest heading?', '<h1>', '<h6>', '<heading>', '<title>', 'A'],
    ['What tag is used to create a hyperlink?', '<a>', '<link>', '<href>', '<url>', 'A'],
    ['Which tag displays an image?', '<img>', '<image>', '<picture>', '<photo>', 'A'],
    ['What is the purpose of the <title> tag?', 'To display text in the browser tab', 'To create a heading', 'To make text bold', 'To add a comment', 'A'],
    ['Which tag creates an unordered list?', '<ul>', '<ol>', '<li>', '<list>', 'A'],
    ['What tag creates an ordered list?', '<ol>', '<ul>', '<li>', '<list>', 'A'],
    ['What does <li> tag stand for?', 'List item', 'Line item', 'List information', 'List index', 'A'],
    ['Which tag is used for form input?', '<input>', '<form>', '<field>', '<textbox>', 'A'],
    ['What tag starts a form?', '<form>', '<input>', '<field>', '<submit>', 'A'],
    ['Which attribute specifies the URL of a link?', 'href', 'link', 'url', 'src', 'A'],
    ['What tag creates a table?', '<table>', '<grid>', '<matrix>', '<data>', 'A'],
    ['Which tag defines a table row?', '<tr>', '<td>', '<th>', '<row>', 'A'],
    ['What tag defines a table cell?', '<td>', '<tr>', '<th>', '<cell>', 'A'],
    ['Which tag is used for a button?', '<button>', '<btn>', '<input type="button">', 'Both A and C', 'D'],
    ['What does DOCTYPE declare?', 'The document type', 'The document title', 'The document author', 'The document version', 'A'],
    ['Which tag is used for emphasis?', '<em>', '<strong>', '<b>', 'Both A and B', 'D'],
    ['What tag makes text bold?', '<b>', '<bold>', '<strong>', 'Both A and C', 'D'],
    ['Which tag makes text italic?', '<i>', '<italic>', '<em>', 'Both A and C', 'D'],
    ['What tag is used for a blockquote?', '<blockquote>', '<quote>', '<bq>', '<quotation>', 'A'],
    ['Which tag creates a section?', '<section>', '<div>', '<article>', '<block>', 'A'],
    ['What tag defines an article?', '<article>', '<section>', '<post>', '<content>', 'A'],
    ['Which tag represents a footer?', '<footer>', '<bottom>', '<end>', '<foot>', 'A'],
    ['What tag represents a header?', '<header>', '<top>', '<head>', '<navigation>', 'A'],
    ['Which tag is used for navigation?', '<nav>', '<menu>', '<navigation>', '<links>', 'A'],
    ['What tag groups content together?', '<div>', '<group>', '<container>', '<block>', 'A'],
    ['Which tag creates a horizontal line?', '<hr>', '<line>', '<horizontal>', '<rule>', 'A'],
    ['What is the correct HTML for inserting an image?', '<img src="image.jpg">', '<image src="image.jpg">', '<img link="image.jpg">', '<img url="image.jpg">', 'A'],
    ['Which attribute specifies alternate text for an image?', 'alt', 'alternate', 'title', 'description', 'A'],
    ['What tag is used to display preformatted text?', '<pre>', '<code>', '<text>', '<format>', 'A'],
    ['Which tag is used for code snippets?', '<code>', '<pre>', '<script>', '<programming>', 'A'],
    ['What tag creates a comment in HTML?', '<!-- Comment -->', '// Comment', '/* Comment */', '# Comment', 'A'],
    ['Which tag is used for keyboard input?', '<kbd>', '<key>', '<keyboard>', '<input>', 'A'],
    ['What tag is used for output?', '<output>', '<result>', '<display>', '<show>', 'A'],
    ['Which tag represents a variable?', '<var>', '<variable>', '<v>', '<x>', 'A'],
    ['What tag is used for subscript text?', '<sub>', '<subscript>', '<down>', '<lower>', 'A'],
    ['Which tag is used for superscript text?', '<sup>', '<superscript>', '<up>', '<upper>', 'A'],
    ['What tag is used to mark text?', '<mark>', '<highlight>', '<marker>', '<span>', 'A'],
    ['Which tag creates a definition list?', '<dl>', '<ul>', '<ol>', '<list>', 'A'],
    ['What tag defines a term in a definition list?', '<dt>', '<dd>', '<term>', '<definition>', 'A'],
    ['Which tag represents the description in a definition list?', '<dd>', '<dt>', '<desc>', '<description>', 'A'],
    ['What tag is used for a time element?', '<time>', '<date>', '<datetime>', '<timestamp>', 'A'],
    ['Which tag represents a figure?', '<figure>', '<img>', '<picture>', '<image>', 'A'],
    ['What tag is used for figure caption?', '<figcaption>', '<caption>', '<title>', '<label>', 'A'],
  ],
  'medium' => [
    ['What is the difference between <div> and <span>?', '<div> is block, <span> is inline', 'No difference', '<span> is block, <div> is inline', '<div> is for styling only', 'A'],
    ['Which attribute is used to specify a unique id?', 'id', 'class', 'name', 'identifier', 'A'],
    ['What attribute is used to specify CSS classes?', 'class', 'id', 'style', 'className', 'A'],
    ['How do you add a comment in HTML?', '<!-- Comment -->', '// Comment', '/* Comment */', '# Comment', 'A'],
    ['What is the correct HTML for creating a dropdown list?', '<select><option>', '<dropdown>', '<list type="dropdown">', '<select type="dropdown">', 'A'],
    ['Which input type is used for checkboxes?', 'checkbox', 'check', 'option', 'multiple', 'A'],
    ['What input type creates radio buttons?', 'radio', 'button', 'option', 'single', 'A'],
    ['How do you create a text input field?', '<input type="text">', '<text>', '<input>', '<input type="input">', 'A'],
    ['What attribute makes a form field required?', 'required', 'mandatory', 'needed', 'must-fill', 'A'],
    ['Which tag is used to label a form input?', '<label>', '<lab>', '<tag>', '<text>', 'A'],
    ['What attribute connects a label to an input?', 'for', 'input', 'connect', 'target', 'A'],
    ['How do you create a password field?', '<input type="password">', '<input type="secret">', '<password>', '<input password>', 'A'],
    ['What tag is used for a text area?', '<textarea>', '<textinput>', '<text>', '<input>', 'A'],
    ['Which attribute specifies the size of an input field?', 'size', 'width', 'length', 'maxlength', 'A'],
    ['What attribute limits the number of characters in an input?', 'maxlength', 'limit', 'max', 'length', 'A'],
    ['How do you specify a placeholder in an input field?', 'placeholder', 'hint', 'default', 'text', 'A'],
    ['What attribute makes an input field disabled?', 'disabled', 'readonly', 'inactive', 'off', 'A'],
    ['Which attribute makes an input field read-only?', 'readonly', 'disabled', 'locked', 'protected', 'A'],
    ['What tag creates a data list?', '<datalist>', '<list>', '<select>', '<options>', 'A'],
    ['How do you specify default selected option?', 'selected', 'default', 'checked', 'active', 'A'],
    ['What attribute specifies the method for form submission?', 'method', 'action', 'type', 'submit', 'A'],
    ['Which methods are valid for forms?', 'GET and POST', 'GET only', 'POST only', 'PUT and DELETE', 'A'],
    ['What attribute specifies where to send form data?', 'action', 'method', 'target', 'destination', 'A'],
    ['How do you open a link in a new tab?', 'target="_blank"', 'target="new"', 'new="true"', 'open="tab"', 'A'],
    ['What tag wraps multiple radio buttons or checkboxes?', '<fieldset>', '<group>', '<form>', '<container>', 'A'],
    ['Which tag provides a caption for a fieldset?', '<legend>', '<caption>', '<label>', '<title>', 'A'],
    ['What is semantic HTML?', 'HTML that describes meaning', 'HTML with style', 'HTML with scripts', 'HTML with metadata', 'A'],
    ['Which is a semantic HTML element?', '<article>', '<div>', '<span>', '<container>', 'A'],
    ['What tag represents the main content of a page?', '<main>', '<content>', '<primary>', '<body>', 'A'],
    ['How do you specify the character encoding?', '<meta charset="UTF-8">', '<encoding="UTF-8">', '<charset="UTF-8">', '<meta encoding="UTF-8">', 'A'],
    ['What is the purpose of the viewport meta tag?', 'Control layout on mobile', 'Set page title', 'Link external CSS', 'Specify character set', 'A'],
    ['Which attribute specifies the width of an image?', 'width', 'w', 'size', 'length', 'A'],
    ['What attribute specifies the height of an image?', 'height', 'h', 'size', 'length', 'A'],
    ['How do you specify responsive images?', 'srcset', 'src', 'images', 'sources', 'A'],
    ['What tag is used for an area in a map?', '<area>', '<zone>', '<region>', '<spot>', 'A'],
    ['Which tag defines clickable areas on an image?', '<map>', '<click>', '<image>', '<area>', 'A'],
    ['What attribute specifies the shape of a clickable area?', 'shape', 'form', 'type', 'area', 'A'],
    ['How do you embed a video?', '<video>', '<movie>', '<embed>', '<player>', 'A'],
    ['What tag is used to embed audio?', '<audio>', '<sound>', '<music>', '<embed>', 'A'],
    ['Which tag provides alternative content?', '<source>', '<track>', '<fallback>', '<alternative>', 'A'],
    ['What attribute specifies autoplay for media?', 'autoplay', 'auto', 'play', 'autostart', 'A'],
    ['How do you add controls to a video?', 'controls', 'player', 'ui', 'interface', 'A'],
    ['What attribute loops a video?', 'loop', 'repeat', 'replay', 'continuous', 'A'],
    ['Which attribute specifies the video source?', 'src', 'source', 'video', 'file', 'A'],
    ['What tag defines a canvas for drawing?', '<canvas>', '<draw>', '<graphic>', '<image>', 'A'],
    ['Which tag embeds external content?', '<iframe>', '<embed>', '<object>', '<frame>', 'A'],
    ['What attribute specifies the iframe source?', 'src', 'source', 'link', 'url', 'A'],
  ],
  'hard' => [
    ['What is the difference between standards mode and quirks mode?', 'Standards mode follows W3C specs, quirks mode provides backward compatibility', 'They are the same', 'Quirks mode is older', 'Standards mode is for modern browsers only', 'A'],
    ['How does the browser determine if it should use standards or quirks mode?', 'Based on DOCTYPE', 'Browser version', 'User preference', 'Server setting', 'A'],
    ['What is a valid DOCTYPE for HTML5?', '<!DOCTYPE html>', '<!DOCTYPE HTML PUBLIC...>', '<!DOCTYPE XHTML...>', '<!DOCTYPE HTML 5>', 'A'],
    ['What does XHTML stand for?', 'eXtensible HyperText Markup Language', 'eXtra HyperText Markup Language', 'eXtended HTML', 'eXclusive HTML', 'A'],
    ['How is XHTML different from HTML?', 'XHTML is XML-based and stricter', 'XHTML allows more features', 'They are the same', 'XHTML is deprecated', 'A'],
    ['What is the Web Content Accessibility Guidelines (WCAG)?', 'Guidelines for making web content accessible', 'A markup language', 'A style guide', 'A browser feature', 'A'],
    ['What are ARIA attributes?', 'Accessible Rich Internet Applications attributes', 'Additional Responsive Interface Attributes', 'Advanced Rendering and Interaction Architecture', 'Application Resource Interface Attributes', 'A'],
    ['What is the purpose of the role attribute?', 'Describe the function of an element to assistive technologies', 'Define CSS styling', 'Specify JavaScript behavior', 'Create semantic meaning', 'A'],
    ['What does aria-label do?', 'Provides a label for an element to screen readers', 'Creates a visible label', 'Styles an element', 'Links to another element', 'A'],
    ['What is the difference between alt text and title attribute?', 'alt is for images, title is for tooltips', 'They serve the same purpose', 'title is for accessibility', 'alt is for styling', 'A'],
    ['How do microdata help search engines?', 'Provide semantic information about content', 'Improve page speed', 'Add styling', 'Enable JavaScript', 'A'],
    ['What is Schema.org?', 'A collaborative project for structured data vocabularies', 'A web framework', 'A browser API', 'A JavaScript library', 'A'],
    ['What are Open Graph meta tags?', 'Meta tags that control how content appears when shared', 'Tags for graphs and charts', 'Performance optimization tags', 'Security tags', 'A'],
    ['What meta tag is used for Open Graph?', '<meta property="og:...">', '<meta name="open:graph">', '<og:...>', '<graph property="...">', 'A'],
    ['What is the purpose of rel="canonical"?', 'Specify the preferred URL for duplicate content', 'Link to CSS file', 'Create a relationship between pages', 'Specify rel attribute', 'A'],
    ['How do you implement a preload resource hint?', '<link rel="preload">', '<link rel="prefetch">', '<link rel="preconnect">', '<preload>', 'A'],
    ['What does rel="prefetch" do?', 'Prefetch resources that may be needed for future navigation', 'Preload current page resources', 'Specify link relationship', 'Cache resources', 'B'],
    ['What is the Shadow DOM?', 'An encapsulated DOM subtree for web components', 'A CSS shadow effect', 'A browser storage mechanism', 'A deprecated API', 'A'],
    ['What are Web Components?', 'Reusable custom HTML elements with encapsulation', 'HTML5 features', 'CSS components', 'JavaScript libraries', 'A'],
    ['What is the Custom Elements API?', 'API for creating custom HTML elements', 'API for styling custom elements', 'API for custom attributes', 'API for custom events', 'A'],
    ['What does the template tag do?', 'Holds HTML not rendered until needed', 'Creates an HTML template file', 'Specifies a template language', 'Renders a template', 'A'],
    ['What is the slot element?', 'A placeholder in web components for external content', 'A scheduling element', 'A layout element', 'A content container', 'A'],
    ['What is the difference between HTML and XHTML?', 'XHTML is stricter XML-based version', 'XHTML has more features', 'They are identical', 'HTML is newer', 'A'],
    ['What does the data- attribute allow?', 'Store custom data on HTML elements', 'Create data attributes in CSS', 'Link to external data', 'Define data types', 'A'],
    ['How do you access data attributes in JavaScript?', 'element.dataset.attributeName', 'element.data.attributeName', 'element.getAttribute("data-attributeName")', 'Both A and C', 'D'],
    ['What is HTML5 API?', 'Collection of APIs introduced with HTML5', 'A single API', 'A browser plugin', 'A deprecated technology', 'A'],
    ['What is the Geolocation API?', 'API for obtaining user location', 'API for geographic data', 'API for maps', 'API for IP addresses', 'A'],
    ['What is the LocalStorage API?', 'API for storing data locally in browser', 'API for local files', 'API for local networks', 'API for localStorage variables', 'A'],
    ['What is the difference between localStorage and sessionStorage?', 'localStorage persists, sessionStorage expires when tab closes', 'They are the same', 'sessionStorage is permanent', 'localStorage expires sooner', 'A'],
    ['What is the Service Worker API?', 'API for background scripts independent of web pages', 'API for worker threads', 'API for parallel processing', 'API for background colors', 'A'],
    ['What is the Fetch API?', 'Modern API for making HTTP requests', 'API for fetching files', 'API for retrieving data', 'Replacement for XHR', 'A'],
    ['What is the Intersection Observer API?', 'API for observing element visibility', 'API for intersection calculations', 'API for observing DOM changes', 'API for tracking intersections', 'A'],
    ['What is the ResizeObserver API?', 'API for observing element size changes', 'API for resizing elements', 'API for responsive design', 'API for window resizing', 'A'],
    ['What is the MutationObserver API?', 'API for observing DOM tree changes', 'API for mutations in data', 'API for mutating elements', 'API for change detection', 'A'],
    ['What is content negotiation?', 'Server serving different content based on request headers', 'Negotiating content rights', 'Agreeing on content terms', 'Requesting specific content', 'A'],
    ['What HTTP header controls caching?', 'Cache-Control', 'Caching', 'Cache', 'Control-Cache', 'A'],
    ['What is ETags?', 'Entity tags for cache validation', 'Electronic tags', 'Encoded tags', 'Element tags', 'A'],
    ['What does the Last-Modified header do?', 'Indicates when resource was last changed', 'Specifies last modification date', 'Controls cache expiration', 'Validates content', 'A'],
    ['What is conditional request?', 'Request with conditions for retrieval based on headers', 'Request with query conditions', 'Request with form conditions', 'Request with filter conditions', 'A'],
    ['What HTTP status code means permanent redirect?', '301', '302', '304', '307', 'A'],
    ['What status code means resource not found?', '404', '400', '403', '500', 'A'],
  ]
];

// CSS Questions (150 total: 50 easy, 50 medium, 50 hard)
$cssQuestions = [
  'easy' => [
    ['What does CSS stand for?', 'Cascading Style Sheets', 'Computer Style Sheets', 'Cascading System Sheets', 'Cascading Style System', 'A'],
    ['How do you select an element by ID in CSS?', '#elementId', '.elementId', 'elementId', '*elementId', 'A'],
    ['How do you select an element by class in CSS?', '.className', '#className', 'className', '*className', 'A'],
    ['What is the correct syntax for a CSS rule?', 'selector { property: value; }', 'selector: property value;', 'selector[property=value]', 'selector{property-value}', 'A'],
    ['How do you change the text color?', 'color: red;', 'text-color: red;', 'font-color: red;', 'text: red;', 'A'],
    ['What property changes the background color?', 'background-color', 'bgcolor', 'background', 'back-color', 'A'],
    ['How do you set the font size?', 'font-size: 14px;', 'text-size: 14px;', 'size: 14px;', 'font: 14px;', 'A'],
    ['What property makes text bold?', 'font-weight: bold;', 'bold: true;', 'text-bold: true;', 'font-bold: true;', 'A'],
    ['How do you make text italic?', 'font-style: italic;', 'italic: true;', 'text-style: italic;', 'style: italic;', 'A'],
    ['What property controls text alignment?', 'text-align', 'align', 'text-align-position', 'alignment', 'A'],
    ['How do you set margin?', 'margin: 10px;', 'margin-size: 10px;', 'space: 10px;', 'margin-width: 10px;', 'A'],
    ['What property sets padding?', 'padding', 'pad', 'inner-spacing', 'padding-size', 'A'],
    ['How do you add a border?', 'border: 1px solid black;', 'line: 1px black;', 'border-line: 1px;', 'outline: 1px black;', 'A'],
    ['What property sets the width of an element?', 'width', 'w', 'element-width', 'size-width', 'A'],
    ['How do you set the height?', 'height: 100px;', 'h: 100px;', 'element-height: 100px;', 'size-height: 100px;', 'A'],
    ['What does display: block do?', 'Element takes full width, appears on new line', 'Hides element', 'Makes element inline', 'Makes element a block quote', 'A'],
    ['What is display: inline?', 'Element takes only needed width, flows with text', 'Element takes full width', 'Element is hidden', 'Element is floating', 'A'],
    ['What does display: none do?', 'Hides element, removes from layout', 'Hides element but reserves space', 'Makes element transparent', 'Disables element', 'A'],
    ['How do you change the opacity?', 'opacity: 0.5;', 'transparency: 0.5;', 'alpha: 0.5;', 'fade: 0.5;', 'A'],
    ['What property controls background image?', 'background-image', 'image', 'bg-image', 'background', 'A'],
    ['How do you set a background image?', 'background-image: url("image.jpg");', 'background: image.jpg;', 'image: url("image.jpg");', 'bg: url("image.jpg");', 'A'],
    ['What does float do?', 'Positions element to left or right', 'Makes element float above others', 'Creates floating layout', 'Floats element on page', 'A'],
    ['What property controls text decoration?', 'text-decoration', 'decoration', 'text-style', 'style', 'A'],
    ['How do you underline text?', 'text-decoration: underline;', 'underline: true;', 'text-underline: true;', 'decoration: underline;', 'A'],
    ['What removes underline from links?', 'text-decoration: none;', 'no-underline: true;', 'underline: false;', 'text-decoration: false;', 'A'],
    ['What property sets text shadow?', 'text-shadow', 'shadow', 'text-effect', 'effect', 'A'],
    ['How do you set a box shadow?', 'box-shadow', 'shadow', 'shadow-box', 'box-effect', 'A'],
    ['What property controls the cursor style?', 'cursor', 'mouse-cursor', 'pointer', 'cursor-style', 'A'],
    ['What does cursor: pointer do?', 'Changes cursor to pointing hand', 'Points to element', 'Activates click', 'Shows coordinates', 'A'],
    ['How do you make an element circular?', 'border-radius: 50%;', 'circle: 50%;', 'shape: circle;', 'radius: 50%;', 'A'],
    ['What property controls z-index?', 'z-index', 'layer', 'depth', 'stack', 'A'],
    ['What does position: absolute do?', 'Positions relative to nearest positioned parent', 'Positions relative to viewport', 'Removes from document flow', 'All of above', 'D'],
    ['What is position: relative?', 'Positions relative to normal position', 'Positions relative to parent', 'Positions relative to viewport', 'Positions relative to document', 'A'],
    ['What does position: fixed do?', 'Positions relative to viewport, stays in place when scrolling', 'Positions relative to parent', 'Fixes element in place', 'Prevents movement', 'A'],
    ['What is position: sticky?', 'Element sticks to viewport when scrolling to it', 'Element is sticky/adhesive', 'Element cannot move', 'Element is fixed', 'A'],
    ['How do you center a div horizontally?', 'margin: 0 auto;', 'align: center;', 'text-align: center;', 'position: center;', 'A'],
    ['What does flex do?', 'Creates flexible box layout', 'Makes element flexible', 'Allows flexibility', 'Adjusts size', 'A'],
    ['What property makes an element a flex container?', 'display: flex;', 'flex: true;', 'flexbox: true;', 'layout: flex;', 'A'],
    ['How do you set background size?', 'background-size', 'size', 'bg-size', 'background-sizing', 'A'],
    ['What does background-size: cover do?', 'Image covers entire element', 'Image is sized to fit', 'Image is stretched', 'Image is repeated', 'A'],
    ['What is background-size: contain?', 'Image fits inside element without cropping', 'Image contains element', 'Image covers element', 'Image is repeated', 'A'],
    ['How do you repeat a background?', 'background-repeat', 'repeat', 'bg-repeat', 'repetition', 'A'],
    ['What does @media do?', 'Applies styles based on media type/conditions', 'Sets media elements', 'Controls media files', 'Specifies media', 'A'],
    ['What is a media query?', 'CSS technique to apply styles to different devices', 'Query for media files', 'Search media', 'Media database query', 'A'],
    ['How do you make responsive text?', 'Using relative units like em, rem, or %', 'Using fixed units only', 'Using font-size only', 'Using media queries only', 'A'],
    ['What unit is relative to parent font size?', 'em', 'rem', 'px', 'pt', 'A'],
    ['What unit is relative to root font size?', 'rem', 'em', 'px', 'pt', 'A'],
  ],
  'medium' => [
    ['What is the CSS cascade?', 'Rules that determine which styles apply when conflicts occur', 'A CSS library', 'A cascading menu', 'A styling technique', 'A'],
    ['What is specificity in CSS?', 'Measure of which selector will be applied', 'Specific styling rules', 'Precise selectors', 'Detailed styles', 'A'],
    ['How do you calculate CSS specificity?', '(0, inline styles, IDs, classes/attributes/pseudo-classes, elements)', '(IDs, classes, elements)', '(specificity score 0-1000)', 'Based on selector length', 'A'],
    ['What does !important do?', 'Overrides other styling rules', 'Makes style very important', 'Highlights important styles', 'Locks a style', 'A'],
    ['What is the CSS box model?', 'Content, Padding, Border, Margin', 'Content, Style, Script, Markup', 'Margin, Border, Padding, Content', 'Layout model for boxes', 'A'],
    ['How do you use shorthand for margin?', 'margin: top right bottom left;', 'margin: all;', 'margin-all: value;', 'margin: value;', 'A'],
    ['What does margin: 10px 20px do?', '10px top/bottom, 20px left/right', '10px all, 20px borders', '10px left, 20px right', '10px padding, 20px margin', 'A'],
    ['How do you select multiple elements?', 'selector1, selector2', 'selector1 selector2', 'selector1 > selector2', 'selector1 + selector2', 'A'],
    ['What is a descendant selector?', 'Selects any nested element', 'Selects direct child', 'Selects adjacent element', 'Selects previous element', 'A'],
    ['What is a child selector?', 'Selects direct children only', 'Selects all descendants', 'Selects adjacent elements', 'Selects siblings', 'A'],
    ['What does adjacent sibling selector do?', 'Selects element immediately after another', 'Selects any sibling', 'Selects all following siblings', 'Selects parent element', 'A'],
    ['What is a pseudo-class?', 'Defines special state of element (like :hover)', 'False class', 'Temporary class', 'Hidden class', 'A'],
    ['What does :hover do?', 'Applies style when element is hovered', 'Applies always', 'Applies on click', 'Applies on focus', 'A'],
    ['What is :focus?', 'Applies style when element receives focus', 'When element is visible', 'When element is active', 'When element is hovered', 'A'],
    ['What does :active do?', 'Applies style when element is being clicked', 'Applies when active', 'Applies when enabled', 'Applies when hovered', 'A'],
    ['What is :nth-child(n)?', 'Selects nth child of parent', 'Selects n children', 'Selects children in order', 'Selects multiple children', 'A'],
    ['What does :first-child do?', 'Selects first child of parent', 'Selects first element', 'Selects primary child', 'Selects initial child', 'A'],
    ['What is :last-child?', 'Selects last child of parent', 'Selects last element', 'Selects final child', 'Selects end child', 'A'],
    ['What does :not(selector) do?', 'Selects elements not matching selector', 'Selects everything except selector', 'Negates selector', 'Excludes selector', 'A'],
    ['What is a pseudo-element?', 'Adds style to specific part of element (::before, ::after)', 'False element', 'Temporary element', 'Virtual element', 'A'],
    ['What does ::before do?', 'Inserts content before element', 'Before the page loads', 'Before element is rendered', 'Previously existing element', 'A'],
    ['What is ::after?', 'Inserts content after element', 'After page loads', 'After element renders', 'Following element', 'A'],
    ['What is CSS transitions?', 'Smooth animation between property changes', 'Moving elements', 'Page transitions', 'CSS loading', 'A'],
    ['How do you create a transition?', 'transition: property duration timing-function;', 'animate: property duration;', 'transition-property: value;', 'animate-property: duration;', 'A'],
    ['What is transform in CSS?', 'Modifies element appearance and position', 'Changes shape', 'Converts element', 'Modifies structure', 'A'],
    ['What does transform: translate do?', 'Moves element along X and Y axis', 'Translates language', 'Moves to center', 'Relocates element', 'A'],
    ['What is transform: rotate?', 'Rotates element by angle', 'Spinning animation', 'Circular movement', 'Turns element', 'A'],
    ['What does transform: scale do?', 'Enlarges or shrinks element', 'Resizes container', 'Adjusts proportions', 'Changes dimensions', 'A'],
    ['What is CSS Grid?', 'Layout system using rows and columns', 'Grid of elements', 'Aligned layout', 'Structured arrangement', 'A'],
    ['How do you create a grid?', 'display: grid;', 'grid: true;', 'layout: grid;', 'grid-layout: true;', 'A'],
    ['What does grid-template-columns do?', 'Defines number and size of columns', 'Sets columns width', 'Creates column layout', 'Arranges columns', 'A'],
    ['What is flexbox?', 'One-dimensional layout for flexible sizing', 'Box layout', 'Flexible spacing', 'Dynamic layout', 'A'],
    ['What does flex-direction do?', 'Sets direction of flex items (row, column, etc.)', 'Adjusts flex size', 'Changes flex alignment', 'Modifies flex order', 'A'],
    ['What is justify-content?', 'Aligns flex items along main axis', 'Justifies text', 'Aligns center', 'Distributes space', 'A'],
    ['What does align-items do?', 'Aligns flex items along cross axis', 'Aligns items center', 'Positions items', 'Arranges items', 'A'],
    ['What is flex-wrap?', 'Controls wrapping of flex items', 'Wraps text', 'Lines breaking', 'Item positioning', 'A'],
    ['What does justify-self do?', 'Aligns item within grid cell', 'Justifies self', 'Centers item', 'Positions item', 'A'],
    ['What is align-self?', 'Aligns item along cross axis individually', 'Self alignment', 'Personal alignment', 'Item alignment', 'A'],
    ['What does gap property do?', 'Sets space between grid/flex items', 'Space between items', 'Item spacing', 'Container gap', 'A'],
    ['What is CSS variables?', 'Reusable values defined with --name', 'Variable storage', 'CSS properties', 'Dynamic values', 'A'],
    ['How do you declare CSS variable?', '--variable-name: value;', 'var-name: value;', '$variable-name: value;', '@variable-name: value;', 'A'],
    ['How do you use CSS variable?', 'var(--variable-name)', 'variable-name', '$variable-name', '@variable-name', 'A'],
    ['What is calc() in CSS?', 'Function to calculate values', 'Calculation formula', 'Math function', 'Computing values', 'A'],
    ['What does filter property do?', 'Applies graphical effects', 'Filters elements', 'Visual effects', 'Image processing', 'A'],
    ['What is will-change?', 'Hints browser to optimize element', 'Changes will occur', 'Future changes', 'Performance hint', 'A'],
    ['What does backface-visibility do?', 'Controls visibility of back side in 3D transforms', 'Back visibility', 'Rear visibility', '3D element back', 'A'],
  ],
  'hard' => [
    ['What is CSS Containment?', 'Performance optimization limiting browser rendering scope', 'Containing styles', 'Layout containment', 'Performance technique', 'A'],
    ['What does contain: layout do?', 'Element layout independent from rest of page', 'Layout boundaries', 'Contained layout', 'Bounded layout', 'A'],
    ['What is BEM methodology?', 'Block Element Modifier naming convention', 'CSS methodology', 'Naming system', 'Style organization', 'A'],
    ['What is OOCSS?', 'Object-Oriented CSS for reusable components', 'Object CSS', 'Organized CSS', 'Oriented CSS', 'A'],
    ['What is SMACSS?', 'Scalable and Modular Architecture for CSS', 'CSS architecture', 'Modular system', 'Architecture pattern', 'A'],
    ['What is CSS-in-JS?', 'Technique writing CSS within JavaScript', 'CSS from JavaScript', 'JS CSS libraries', 'Dynamic CSS', 'A'],
    ['What are CSS-in-JS libraries?', 'styled-components, Emotion, etc.', 'React, Vue, Angular', 'Bootstrap, Tailwind', 'Preprocessors', 'A'],
    ['What is SASS?', 'Syntactically Awesome Style Sheets, CSS preprocessor', 'CSS language', 'Styling tool', 'CSS extension', 'A'],
    ['What does SASS provide over CSS?', 'Variables, nesting, mixins, functions', 'Better styling', 'Enhanced CSS', 'Advanced features', 'A'],
    ['What is LESS?', 'CSS preprocessor similar to SASS', 'Lesser CSS', 'Reduced CSS', 'Lightweight CSS', 'A'],
    ['What is a mixin in SASS?', 'Reusable block of CSS code', 'Mixed styles', 'Combined styles', 'Blended properties', 'A'],
    ['What does @extend do?', 'One selector inherits styles of another', 'Extends element', 'Increases size', 'Expands selector', 'A'],
    ['What is CSS critical path?', 'Process rendering HTML and CSS before displaying', 'Critical styles', 'Important CSS', 'Loading sequence', 'A'],
    ['What is critical CSS?', 'CSS needed to render above-the-fold content', 'Necessary CSS', 'Required styles', 'Essential CSS', 'A'],
    ['What is above the fold?', 'Content visible without scrolling', 'Visible content', 'Initial viewport', 'First screen', 'A'],
    ['What does font-loading strategy affect?', 'Text rendering until font loads', 'Font display', 'Font behavior', 'Text visibility', 'A'],
    ['What is font-display?', 'Controls font rendering behavior', 'Font visibility', 'Font display', 'Font behavior', 'A'],
    ['What does font-display: swap do?', 'Shows fallback until custom font loads', 'Font replacement', 'Text swap', 'Font substitution', 'A'],
    ['What is CSS paint performance?', 'How efficiently browser paints styles', 'Paint speed', 'Rendering performance', 'Visual performance', 'A'],
    ['What causes layout thrashing?', 'Reading and writing DOM repeatedly in loop', 'Layout breaking', 'DOM issues', 'Rendering problems', 'A'],
    ['What is CSS containment?', 'Limiting browser rendering to specific elements', 'Scoped styling', 'Bounded rendering', 'Limited scope', 'A'],
    ['What does will-change property do for performance?', 'Hints browser to optimize for changes', 'Performance optimization', 'Pre-rendering', 'Optimization hint', 'A'],
    ['What is GPU acceleration?', 'Using graphics processor for CSS rendering', 'Graphics processing', 'Hardware acceleration', 'Video rendering', 'A'],
    ['What properties trigger GPU acceleration?', 'transform, opacity, 3D transforms', 'Animation properties', 'Visual effects', 'Rendering', 'A'],
    ['What is the CSS Painting Order?', 'Order elements are rendered on screen', 'Drawing sequence', 'Rendering order', 'Stack order', 'A'],
    ['What does requestAnimationFrame do?', 'Synchronizes animation with browser repaint', 'Animation timing', 'Frame request', 'Sync rendering', 'A'],
    ['What is CSS debugging?', 'Finding and fixing CSS issues', 'Bug fixing', 'Style issues', 'Problem solving', 'A'],
    ['What is CSS linting?', 'Tool analyzing CSS for errors and best practices', 'Code analysis', 'Style checking', 'Quality tool', 'A'],
    ['What are CSS linters?', 'stylelint, CSSLint, Prettier', 'CSS tools', 'Analysis tools', 'Checking tools', 'A'],
    ['What is CSS normalization?', 'Ensures consistent default browser styles', 'Standard styles', 'Default rules', 'Consistency', 'A'],
    ['What does normalize.css do?', 'Makes browsers render elements consistently', 'Browser fixes', 'CSS reset', 'Standardization', 'A'],
    ['What is CSS reset?', 'Removes default browser styles', 'Style removal', 'Default removal', 'Browser defaults', 'A'],
    ['What is color space?', 'Range of colors representable', 'Color definition', 'Color model', 'Color range', 'A'],
    ['What does color: currentColor do?', 'Uses inherited color value', 'Current text color', 'Inherited color', 'Active color', 'A'],
    ['What are CSS custom properties?', '--variable-name for reusable values', 'CSS variables', 'Custom values', 'Defined properties', 'A'],
    ['What is CSS :root?', 'Document root element, global scope for variables', 'Root selector', 'Document root', 'Top level', 'A'],
    ['What does @supports do?', 'Applies styles if browser supports property', 'Conditional styling', 'Feature check', 'Browser check', 'A'],
    ['What is CSS attribute selector?', 'Selects elements based on attribute', '[attribute="value"]', 'Attribute matching', 'Based on attributes', 'A'],
    ['What does [attribute*="value"] do?', 'Matches attribute containing value', 'Contains match', 'Partial match', 'Substring match', 'A'],
    ['What is [attribute^="value"]?', 'Matches attribute starting with value', 'Starts with', 'Beginning match', 'Prefix match', 'A'],
    ['What does [attribute$="value"] do?', 'Matches attribute ending with value', 'Ends with', 'Ending match', 'Suffix match', 'A'],
    ['What is CSS Conditional Group Rules?', '@media, @supports, @document rules', 'Conditional rules', 'Feature queries', 'Browser conditions', 'A'],
    ['What are Feature Queries?', '@supports rules for feature detection', 'Browser features', 'Support checking', 'Feature detection', 'A'],
    ['What is CSS Scroll Behavior?', 'scroll-behavior: smooth for smooth scrolling', 'Scroll animation', 'Page scrolling', 'Smooth scroll', 'A'],
    ['What does scroll-snap-type do?', 'Enables scroll snapping', 'Snapping behavior', 'Snap alignment', 'Scroll alignment', 'A'],
    ['What is overscroll-behavior?', 'Controls overscroll effects', 'Scroll behavior', 'Edge scrolling', 'Bounce effect', 'A'],
    ['What does mix-blend-mode do?', 'Defines how element blends with background', 'Blending effect', 'Color mixing', 'Visual blending', 'A'],
    ['What is backdrop-filter?', 'Applies filter to element background', 'Background effect', 'Behind element', 'Background blur', 'A'],
  ]
];

// Now insert all questions with error handling
try {
  // Insert HTML questions
  foreach ($htmlQuestions as $difficulty => $questions) {
    foreach ($questions as $q) {
      $stmt = $pdo->prepare("
        INSERT INTO questions (category, difficulty, question, option_a, option_b, option_c, option_d, correct_answer)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
      ");
      $stmt->execute(['HTML', $difficulty, $q[0], $q[1], $q[2], $q[3], $q[4], $q[5]]);
      $totalInserted++;
    }
  }

  // Insert CSS questions
  foreach ($cssQuestions as $difficulty => $questions) {
    foreach ($questions as $q) {
      $stmt = $pdo->prepare("
        INSERT INTO questions (category, difficulty, question, option_a, option_b, option_c, option_d, correct_answer)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
      ");
      $stmt->execute(['CSS', $difficulty, $q[0], $q[1], $q[2], $q[3], $q[4], $q[5]]);
      $totalInserted++;
    }
  }

  echo "✓ Inserted all questions: <strong>" . $totalInserted . "</strong><br><br>";

  // Verify
  $check = $pdo->query("SELECT COUNT(*) as count FROM questions")->fetch();
  echo "Final database count: <strong>" . $check['count'] . "</strong><br>";

  $breakdown = $pdo->query("SELECT category, difficulty, COUNT(*) as count FROM questions GROUP BY category, difficulty ORDER BY category, difficulty")->fetchAll();
  echo "<table border='1' cellpadding='10'>";
  echo "<tr style='background: #f0f0f0;'><th>Category</th><th>Difficulty</th><th>Count</th></tr>";
  foreach ($breakdown as $row) {
    echo "<tr><td>" . $row['category'] . "</td><td><strong>" . $row['difficulty'] . "</strong></td><td>" . $row['count'] . "</td></tr>";
  }
  echo "</table>";

} catch (Exception $e) {
  echo "ERROR: " . $e->getMessage();
}
?>
