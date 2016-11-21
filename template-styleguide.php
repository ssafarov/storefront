<?php
/**
 * Template name: Styleguide
 */

get_header();

?>
<style>
	li { margin: 5px 0; }
	table th,
	table td { vertical-align: middle; }
	tbody th { width: 400px; }
</style>
<main class="site-main styleguide-wrap container">
	<div class="row">
		<div class="col-sm-12">

			<h1>H1 Lorem ipsum</h1>
			<h2>H2 Lorem ipsum</h2>
			<h3>H3 Lorem ipsum</h3>
			<h4>H4 Lorem ipsum</h4>
			<h5>H5 Lorem ipsum</h5>
			<h6>H6 Lorem ipsum</h6>

			<hr>

			<h3>Button types</h3>
			<h5>Can be used on:</h5>
			<ul>
				<li><code>a class="btn btn-<strong>{type}</strong>" href="#">Click me!</code></li>
				<li><code>button class="btn btn-<strong>{type}</strong>">Click me!</code></li>
				<li><code>input class="btn btn-<strong>{type}</strong>" type="submit" value="Click me!"</code></li>
			</ul>

			<p>
				<code>
				{type} = [
					'primary' => 'Primary action button',
					'secondary' => 'Secondary  action button',
					'tertiary' => 'Tertiary  action button'
				]
				</code>
			</p>

			<table>
				<tbody>
					<tr>
						<th><a href="#" class="btn btn-primary">Primary</a></th>
						<td><code>.btn.btn-primary</code></td>
					</tr>
					<tr>
						<th><a href="#" class="btn btn-secondary">Secondary</a></th>
						<td><code>.btn.btn-secondary</code></td>
					</tr>
					<tr>
						<th><a href="#" class="btn btn-tertiary">Tertiary</a></th>
						<td><code>.btn.btn-tertiary</code></td>
					</tr>
				</tbody>
			</table>

			<hr>

			<h3>Button sizes</h3>
			<h5>Can be used on:</h5>
			<ul>
				<li><code>a class="btn btn-<strong>{size}</strong>" href="#">Click me!</code></li>
				<li><code>button class="btn btn-<strong>{size}</strong>">Click me!</code></li>
				<li><code>input class="btn btn-<strong>{size}</strong>" type="submit" value="Click me!"</code></li>
			</ul>
			
			<p>
				<code>
				{size} = [
					'sm' => 'small',
					'xs' => 'extra small',
					'lg' => 'large (<strong>TBD</strong>)'
				]
				</code>
			</p>

			<table>
				<thead>
					<tr>
						<th colspan="2">
							<h6>Small buttons</h6>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th><a href="#" class="btn btn-primary btn-sm">Primary Small</a></th>
						<td><code>.btn.btn-primary.btn-sm</code></td>
					</tr>
					<tr>
						<th><a href="#" class="btn btn-secondary btn-sm">Secondary Small</a></th>
						<td><code>.btn.btn-secondary.btn-sm</code></td>
					</tr>
					<tr>
						<th><a href="#" class="btn btn-tertiary btn-sm">Tertiary Small</a></th>
						<td><code>.btn.btn-tertiary.btn-sm</code></td>
					</tr>
				</tbody>
			</table>

			<table>
				<thead>
					<tr>
						<th colspan="2">
							<h6>Extra Small buttons</h6>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th><a href="#" class="btn btn-primary btn-xs">Primary Extra Small</a></th>
						<td><code>.btn.btn-primary.btn-xs</code></td>
					</tr>
					<tr>
						<th><a href="#" class="btn btn-secondary btn-xs">Secondary Extra Small</a></th>
						<td><code>.btn.btn-secondary.btn-xs</code></td>
					</tr>
					<tr>
						<th><a href="#" class="btn btn-tertiary btn-xs">Tertiary Extra Small</a></th>
						<td><code>.btn.btn-tertiary.btn-xs</code></td>
					</tr>
				</tbody>
			</table>

			<hr>

			<h3>Button styles</h3>
			<h5>Can be used on:</h5>
			<ul>
				<li><code>a class="btn btn-<strong>{style}</strong>" href="#">Click me!</code></li>
				<li><code>button class="btn btn-<strong>{style}</strong>">Click me!</code></li>
				<li><code>input class="btn btn-<strong>{style}</strong>" type="submit" value="Click me!"</code></li>
			</ul>
			
			<p>
				<code>
				{style} = [
					'outline',
					'btn-round'
				]
				</code>
			</p>

			<table>
				<thead>
					<tr>
						<th colspan="2">
							<h6>Outline buttons</h6>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>
							<a href="#" class="btn btn-primary btn-outline">Primary Outline</a><br><br>
							<a href="#" class="btn btn-primary btn-sm btn-outline">Primary Outline S</a><br><br>
							<a href="#" class="btn btn-primary btn-xs btn-outline">Primary Outline XS</a>
						</th>
						<td>
							<code>.btn.btn-primary.btn-outline</code><br><br>
							<code>.btn.btn-primary.btn-sm.btn-outline</code><br><br>
							<code>.btn.btn-primary.btn-xs.btn-outline</code>
						</td>
					</tr>
					<tr>
						<th>
							<a href="#" class="btn btn-secondary btn-outline">Secondary Outline</a><br><br>
							<a href="#" class="btn btn-secondary btn-sm btn-outline">Secondary Outline S</a><br><br>
							<a href="#" class="btn btn-secondary btn-xs btn-outline">Secondary Outline XS</a>
						</th>
						<td>
							<code>.btn.btn-secondary.btn-outline</code><br><br>
							<code>.btn.btn-secondary.btn-sm.btn-outline</code><br><br>
							<code>.btn.btn-secondary.btn-xs.btn-outline</code>
						</td>
					</tr>
					<tr>
						<th>
							<a href="#" class="btn btn-tertiary btn-outline">Tertiary Outline</a><br><br>
							<a href="#" class="btn btn-tertiary btn-sm btn-outline">Tertiary Outline S</a><br><br>
							<a href="#" class="btn btn-tertiary btn-xs btn-outline">Tertiary Outline XS</a>
						</th>
						<td>
							<code>.btn.btn-tertiary.btn-outline</code><br><br>
							<code>.btn.btn-tertiary.btn-sm.btn-outline</code><br><br>
							<code>.btn.btn-tertiary.btn-xs.btn-outline</code>
						</td>
					</tr>
				</tbody>
			</table>

			<table>
				<thead>
					<tr>
						<th colspan="2">
							<h6>Pill buttons</h6>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>
							<a href="#" class="btn btn-primary btn-round">Primary Pill</a><br><br>
							<a href="#" class="btn btn-primary btn-sm btn-round">Primary Pill S</a><br><br>
							<a href="#" class="btn btn-primary btn-xs btn-round">Primary Pill XS</a>
						</th>
						<td>
							<code>.btn.btn-primary.btn-round</code><br><br>
							<code>.btn.btn-primary.btn-sm.btn-round</code><br><br>
							<code>.btn.btn-primary.btn-xs.btn-round</code>
						</td>
					</tr>
					<tr>
						<th>
							<a href="#" class="btn btn-secondary btn-round">Secondary Pill</a><br><br>
							<a href="#" class="btn btn-secondary btn-sm btn-round">Secondary Pill s</a><br><br>
							<a href="#" class="btn btn-secondary btn-xs btn-round">Secondary Pill XS</a>
						</th>
						<td>
							<code>.btn.btn-secondary.btn-round</code><br><br>
							<code>.btn.btn-secondary.btn-sm.btn-round</code><br><br>
							<code>.btn.btn-secondary.btn-xs.btn-round</code>
						</td>
					</tr>
					<tr>
						<th>
							<a href="#" class="btn btn-tertiary btn-round">Tertiary Pill</a><br><br>
							<a href="#" class="btn btn-tertiary btn-sm btn-round">Tertiary Pill S</a><br><br>
							<a href="#" class="btn btn-tertiary btn-xs btn-round">Tertiary Pill XS</a>
						</th>
						<td>
							<code>.btn.btn-tertiary.btn-round</code><br><br>
							<code>.btn.btn-tertiary.btn-sm.btn-round</code><br><br>
							<code>.btn.btn-tertiary.btn-xs.btn-round</code>
						</td>
					</tr>
				</tbody>
			</table>

			<hr>

			<h3>Tabs <br><code>.btn.btn-primary.btn-outline</code><br><code>.btn.btn-primary.btn-outline.active</code></h3>

			<p>
				<a href="#" class="btn btn-primary btn-outline btn-sm">Tab 1</a>
				<a href="#" class="btn btn-primary btn-outline btn-sm active">Active</a>
				<a href="#" class="btn btn-primary btn-outline btn-sm">Tab 2</a>
				<a href="#" class="btn btn-primary btn-outline btn-sm">Tab 3</a>
			</p>

			<hr>

			<h3>Pill tabs <br><code>.btn.btn-primary.btn-outline.btn-round</code><br><code>.btn.btn-primary.btn-outline.btn-round.active</code></h3>

			<p>
				<a href="#" class="btn btn-primary btn-outline btn-round btn-sm">Tab 1</a>
				<a href="#" class="btn btn-primary btn-outline btn-round btn-sm active">Active</a>
				<a href="#" class="btn btn-primary btn-outline btn-round btn-sm">Tab 2</a>
				<a href="#" class="btn btn-primary btn-outline btn-round btn-sm">Tab 3</a>
			</p>

		</div>
	</div>
</main>
<?php get_footer(); ?>