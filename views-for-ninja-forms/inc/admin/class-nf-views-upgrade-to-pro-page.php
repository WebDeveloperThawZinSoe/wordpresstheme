<?php
class NF_Views_Upgrade_to_Pro_Page {

	function __construct(){
		add_action('admin_menu', array( $this,'add_page') );
	}

	function add_page(){
		add_submenu_page(
			'edit.php?post_type=nf-views',
			__( 'Get Pro', 'textdomain' ),
			__( '<strong style="color: #FCB214;">Get Pro</strong>', 'textdomain' ),
			'manage_options',
			'nf-views-get-pro',
			array( $this, 'upgrade_to_pro_page')
		);
	}

	function upgrade_to_pro_page(){
		?>
		<style>
			#nf-views-upgrade-section{
				margin: 32px;
				font-size: 1rem;
			}
			#nf-views-upgrade-section h2{
				font-size: 1.88em;
				line-height: 2.5rem;
				margin-bottom: 1.2rem;
			}
			.nf-views-heading-highlight {
				color: #cd631d;
				font-weight: 600;
			}
			.nf-views-pro-benefits li {
				list-style: none!important;
				position: relative;
				padding-left: 1.2533333333rem;
				height: 30px;
			}
			.nf-views-pro-benefits span{
				line-height: 30px;
			}
			.nf-views-pro-benefits .dashicons-yes{
				color:green;
				font-size:32px;
			}
			.nf-views-pro-benefits__title {
				font-weight: 600;
				padding-left: 10px;
			}
			.nf-views-pro-benefits__description:before {
				content: "– ";
			}
			.nf-views-upsell{
				display: inline-flex;
				align-items: center;
				justify-content: center;
				box-sizing: border-box;
				min-height: 48px;
				padding: 8px 1em;
				font-size: 16px;
				line-height: 1.5;
				font-family: Arial,sans-serif;
				color: #ffffff;
				border-radius: 4px;
				box-shadow: inset 0 -4px 0 rgba(0,0,0,.2);
				filter: drop-shadow(0 2px 4px rgba(0,0,0,.2));
				text-decoration: none;
				background-color: #FF9800 ;
			}
			.nfviews-pro-features{
				    display: flex;
				flex-wrap: wrap;
				margin-left: -20px;
				margin-right: -20px;
			}
			.nfviews-pro-feature-cont{
			    padding: 0px 25px;
				float: left;
				width: 20%;
				margin-bottom: 50px;
				position: relative;
			}
			.nfviews-pro-feature{
				background-color: #fff;
				border: 1px solid #ddd;
				border-radius: 0px;
				margin: 0;
				padding: 10px;
				border-bottom: 0px;
			}
			.nfviews-pro-feature h5{
					text-align: center;
					font-size: 16px;
					margin-bottom: 10px;
			}
			.nfviews-pro-feature p{
					text-align: center;
					font-size: 14px;
			}
			.nfviews-pro-feature p.icon{
				text-align: center;
				color:#0073e6;
				background-color:transparent;
			}
			.nfviews-pro-feature-cont .actions{
				align-items: center;
				background-color: #f7f7f7;
				border: 1px solid #ddd;
				padding: 20px;
				position: relative;
				text-align: center;
			}
			.nfviews-pro-feature-cont .actions a{
				background: none;
				border: 1px solid #ddd;
				border-radius: 3px;
				box-shadow: none;
				font-weight: 600;
				width: 140px;
				text-align: center;
				padding: 8px 12px;
				color: #000;
				text-decoration: none;
				font-size: 14px;
			}
			.nfviews-pro-feature-cont .license{
				position: absolute;
				right: 26px;
				top: 3px;
			}
			.nfviews-pro-feature-cont .license-label{
				font-size: 11px;
				/* border: 1px solid #ccc; */
				background: #FF9800;
				color: #fff;
				padding: 7px;
			}
			.nfviews-pro-feature-cont .license-label.developer{
				background: #4caf50;
			}
			.nfviews-pro-feature-cont .license-label.professional{
				background: #00bcd4;
			}
		</style>

		<div id="nf-views-upgrade-section">
			<h2><span class="nf-views-heading-highlight">Views for Ninja Forms Pro</span>, take your Views to next level!</h2>
			<ul class="nf-views-pro-benefits ">
					<li class="nf-views-pro-benefits__item">
						<span class="dashicons dashicons-yes"></span>
						<span class="nf-views-pro-benefits__title">Edit Entries</span>
						<span class="nf-views-pro-benefits__description">allow users to edit Ninja Forms submissions from site frontend.</span>
					</li>
					<li class="nf-views-pro-benefits__item">
						<span class="dashicons dashicons-yes"></span>
						<span class="nf-views-pro-benefits__title">Filter & Sorting</span>
						<span class="nf-views-pro-benefits__description">filter & sort view by form field values.</span>
					</li>
					<li class="nf-views-pro-benefits__item">
						<span class="dashicons dashicons-yes"></span>
						<span class="nf-views-pro-benefits__title">List & DataTable View</span>
						<span class="nf-views-pro-benefits__description">display entries in List View or DataTable View.</span>
					</li>
					<li class="nf-views-pro-benefits__item">
						<span class="dashicons dashicons-yes"></span>
						<span class="nf-views-pro-benefits__title">Search</span>
						<span class="nf-views-pro-benefits__description">allow users to search data in view.</span>
					</li>
					<li class="nf-views-pro-benefits__item">
						<span class="dashicons dashicons-yes"></span>
						<span class="nf-views-pro-benefits__title">Single Entry View</span>
						<span class="nf-views-pro-benefits__description">display Entry details on single page.</span>
					</li>
					<li class="nf-views-pro-benefits__item">
						<span class="dashicons dashicons-yes"></span>
						<span class="nf-views-pro-benefits__title">Premium Support</span>
						<span class="nf-views-pro-benefits__description">access to premium support.</span>
					</li>
				</ul>
				<br/>
			<h3>Features:</h3>
			<br/>
			<div class="nfviews-pro-features ">
				<div class="nfviews-pro-feature-cont">
					<div class="nfviews-pro-feature">
						<div class="license">
							<span class="license-label">Personal</span>
						</div>
						<p class="icon"><i class="fa fa-table fa-2x" > </i></p>
						<h5 class="feature-name">List & DataTable View</h5>
						<p class="feature-desc">Flexibility to display data in either table, list or datatable view.</p>
					</div>

					<div class="actions">
						<div class="action-button">
							<a href="https://nfviews.com/list-view/?utm_source=wordpress-plugin-dashboard&utm_medium=nf-views-upgrade-page&utm_campaign=nf-views-lite-version" target="_blank" class="">Learn more</a>
						</div>
					</div>
				</div>
				<div class="nfviews-pro-feature-cont">
					<div class="nfviews-pro-feature">
					<div class="license">
							<span class="license-label developer">Developer</span>
						</div>
						<p class="icon"><i class="fa fa-edit fa-2x" > </i></p>
						<h5 class="feature-name">Edit Entries</h5>
						<p class="feature-desc">Allow users to Edit their submitted Entries from site frontend.</p>
					</div>

					<div class="actions">
						<div class="action-button">
							<a href="https://nfviews.com/how-to-edit-ninja-forms-submissions-from-site-frontend/?utm_source=wordpress-plugin-dashboard&utm_medium=nf-views-upgrade-page&utm_campaign=nf-views-lite-version" target="_blank" class="">Learn more</a>
						</div>
					</div>
				</div>
				<div class="nfviews-pro-feature-cont">
					<div class="nfviews-pro-feature">
					<div class="license">
							<span class="license-label">Personal</span>
						</div>
						<p class="icon"><i class="fa fa-filter fa-2x"> </i></p>
						<h5 class="feature-name">Advanced Filtering</h5>
						<p class="feature-desc">Filter data by any field/fields to show only submissions which meet the set criteria.</p>
					</div>

					<div class="actions">
						<div class="action-button">
							<a href="https://nfviews.com/docs/filter-ninjaforms-entries-by-field-value/?utm_source=wordpress-plugin-dashboard&utm_medium=nfviews-upgrade-page&utm_campaign=nfviews-lite-version" target="_blank" class="">Learn more</a>
						</div>
					</div>
				</div>
				<div class="nfviews-pro-feature-cont">
					<div class="nfviews-pro-feature">
					<div class="license">
							<span class="license-label">Personal</span>
						</div>
						<p class="icon"><i class="fa fa-search fa-2x"> </i></p>
						<h5 class="feature-name">Search Widget</h5>
						<p class="feature-desc">Allow users to search view entries using any form fields in your search form.</p>
					</div>

					<div class="actions">
						<div class="action-button">
							<a href="https://demo.nfviews.com/?utm_source=wordpress-plugin-dashboard&utm_medium=nfviews-upgrade-page&utm_campaign=nfviews-lite-version" target="_blank" class="">Learn more</a>
						</div>
					</div>
				</div>
				<div class="nfviews-pro-feature-cont">
					<div class="nfviews-pro-feature">
					<div class="license">
							<span class="license-label developer">Developer</span>
						</div>
						<p class="icon"><i class="fa fa-file fa-2x"> </i></p>
						<h5 class="feature-name">Single Entry View</h5>
						<p class="feature-desc">Display only important data in View and all other data in separate page.</p>
					</div>

					<div class="actions">
						<div class="action-button">
							<a href="https://nfviews.com/docs/how-to-display-entry-details-on-single-page/?utm_source=wordpress-plugin-dashboard&utm_medium=nfviews-upgrade-page&utm_campaign=nfviews-lite-version" target="_blank" class="">Learn more</a>
						</div>
					</div>
				</div>
				<div class="nfviews-pro-feature-cont">
					<div class="nfviews-pro-feature">
					<div class="license">
							<span class="license-label">Personal</span>
						</div>
						<p class="icon"><i class="fa fa-sort fa-2x"> </i></p>
						<h5 class="feature-name">Advance Sorting</h5>
						<p class="feature-desc">Sort form entries by fields values in ascending or descending order</p>
					</div>

					<div class="actions">
						<div class="action-button">
							<a href="https://nfviews.com/docs/sort-results-by-field-value/?utm_source=wordpress-plugin-dashboard&utm_medium=nfviews-upgrade-page&utm_campaign=nfviews-lite-version" target="_blank" class="">Learn more</a>
						</div>
					</div>
				</div>
				<div class="nfviews-pro-feature-cont">
					<div class="nfviews-pro-feature">
					<div class="license">
							<span class="license-label professional">Professional</span>
						</div>
						<p class="icon"><i class="fa fa-check-circle fa-2x"> </i></p>
						<h5 class="feature-name">Approve Entries</h5>
						<p class="feature-desc">Admin can display all Entries in View or only those Entries which are aprroved.</p>
					</div>

					<div class="actions">
						<div class="action-button">
							<a href="https://nfviews.com/docs/how-to-show-only-approved-ninjaforms-entries/?utm_source=wordpress-plugin-dashboard&utm_medium=nfviews-upgrade-page&utm_campaign=nfviews-lite-version" target="_blank" class="">Learn more</a>
						</div>
					</div>
				</div>
				<div class="nfviews-pro-feature-cont">
					<div class="nfviews-pro-feature">
					<div class="license">
							<span class="license-label">Personal</span>
						</div>
						<p class="icon"><i class="fa fa-html5 fa-2x"> </i></p>
						<h5 class="feature-name">Custom HTML</h5>
						<p class="feature-desc">Display custom HTML data anywhere in view. Show it before/after the table or in table-cells.</p>
					</div>

					<div class="actions">
						<div class="action-button">
							<a href="https://nfviews.com/pricing/?utm_source=wordpress-plugin-dashboard&utm_medium=nfviews-upgrade-page&utm_campaign=nfviews-lite-version" target="_blank" class="">Learn more</a>
						</div>
					</div>
				</div>


			</div>

				<a class="nf-views-upsell" target="_blank" href="https://nfviews.com/pricing/?utm_source=wordpress-plugin-dashboard&utm_medium=nf-views-upgrade-page&utm_campaign=nf-views-lite-version"> Buy Views for Ninja Forms Pro</a>
		</div>

		<?php
	}


}

new NF_Views_Upgrade_to_Pro_Page();