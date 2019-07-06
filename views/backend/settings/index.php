<?php
use yiiCustom\models\RefModuleSetting;
use yii\widgets\ActiveForm;
use yiiCustom\backend\controllers\SettingsController;
use yiiCustom\core\View;

/**
 * @var View               $this
 * @var RefModuleSetting[] $settings Настройки
 * @var string[]           $errors   Ошибки
 */
?>

<?php if (count($settings) === 0): ?>
	<div class="alert alert-info">У данного модуля нет настроек</div>

	<?php return ?>
<?php endif ?>

<?php if (count($errors) > 0): ?>
	<div class="alert alert-warning">
		<ul class="list-unstyled">
			<?php foreach ($errors as $param => $error): ?>
				<li><?= $settings[$param]->title ?>: <?= $error ?></li>
			<?php endforeach ?>
		</ul>
	</div>
<?php endif ?>

<?php $form = ActiveForm::begin() ?>
<table class="table table-bordered table-condensed table-hover table-small">
	<thead>
	<tr>
		<th>Название</th>
		<th>Значение</th>
	</tr>
	</thead>

	<tbody>
	<?php foreach ($settings as $setting): ?>
		<tr>
			<td><?= $setting->title ?></td>
			<td>
				<?php if ($setting->type_cast === $setting::TYPE_ARRAY): ?>
					<textarea name="<?= SettingsController::PARAM_SCOPE ?>[<?= $setting->param_name ?>]"
					          cols="50"
					          rows="5"
					><?= implode("\n", $setting->getValue()) ?></textarea>
				<?php else: ?>
					<input type="text" class="form-control" name="<?= SettingsController::PARAM_SCOPE ?>[<?= $setting->param_name ?>]" value="<?= $setting->getValue() ?>">
				<?php endif ?>
			</td>
		</tr>
	<?php endforeach ?>
	</tbody>
</table>

<div class="form-group">
	<button type="submit" class="btn btn-primary">Сохранить</button>
</div>
<?php $form = ActiveForm::end() ?>
