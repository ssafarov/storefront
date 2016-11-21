<?php

class TimelineEvents {

	private $data = [];

	/**
	 * Compare two dates in the DD/MM/YYYY format
	 *
	 * @param string $a
	 * @param string $b
	 *
	 * @return int
	 */
	private function dateCompare($a, $b)
	{
		$a = implode('-', array_reverse(explode('/', $a['date'])));
		$b = implode('-', array_reverse(explode('/', $b['date'])));

		if ($a == $b) return 0;

		return $a > $b ? -1 : 1;
	}

	/**
	 * Add a new event
	 *
	 * @param string $label
	 * @param string $date: in DD/MM/YYYY format
     * @param string $icon
	 * @param array $actions
	 *
	 * @return void
	 */
	public function add($label, $date, $icon = null, array $actions = [])
	{
		$datePart = explode('/', $date);
		$year     = $datePart[2];
		$month    = $datePart[1];
		$day      = $datePart[0];

		if ( ! isset($this->data[$year]))
		{
			$this->data[$year] = [];
			krsort($this->data);
		}

		$this->data[$year][] = [
			'label'   => $label,
			'date'    => $date,
            'icon'    => $icon,
			'actions' => $actions,
		];

		usort($this->data[$year], [$this, 'dateCompare']);
	}

	/**
	 * Output events as HTML
	 *
	 * @return string
	 */
	public function toHtml()
	{
		ob_start();
		$i = 0;

		foreach ($this->data as $year => $tab) { ?>
		<div class="panel panel-default">
			<div class="panel-heading" role="tab">
				<h3>
					<a data-toggle="collapse" data-parent="#accordion-timeline" href="#tl-tab<?= ++$i ?>" aria-expanded="<?= $i == 1 ? 'true' : 'false' ?>" aria-controls="tl-tab<?= $i ?>" class="<?= $i == 1 ? '' : 'collapsed'; ?>">
						<?=$year?>
						<span class="arrow">
							<i class="icon-arrow-down"></i>
							<i class="icon-arrow-up"></i>
						</span>
					</a>
				</h3>
			</div>
			<div id="tl-tab<?=$i?>" class="<?= $i == 1 ? 'panel-collapse in ' : ''; ?>collapse" role="tabpanel">
				<div class="panel-body">
					<?php foreach ($tab as $event) { ?>
					<div class="row">
						<div class="col-sm-3 col-sm-push-9 text-right timeline-date">
							<p>
								<?=$event['date']?>
							</p>
						</div>
						<div class="col-sm-9 col-sm-pull-3">
							<p>
								<?=$event['icon'] ? '<i class="icon-' . $event['icon'] . '"></i> ' : '' ?><?=$event['label']?>
								<?php foreach($event['actions'] as $j => $action) { ?>
								<?=($j == 0) ? '<br>' : ' | '?>
								<?=( ! empty($action['name']) && ! empty($action['url'])) ? '<a class="action'.($j==0?' first':'').'" href="'. $action['url'] . '">' . $action['name'] . '</a>' : ''?>
								<?php } ?>
							</p>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php }

		$ob = ob_get_contents();
		ob_end_clean();
		return $ob;
	}

}