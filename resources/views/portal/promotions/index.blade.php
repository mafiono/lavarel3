@extends('portal.profile.layout', [
    'active1' => 'promocoes',
    'middle' => 'portal.promotions.head_promotions',
    'active2' => 'por_utilizar'])

@section('sub-content')

	<div class="col-xs-12 lin-xs-11 fleft">
		<div class="box-form-user-info lin-xs-12">
			<div class="title-form-registo brand-title brand-color aleft">
				Promoções e Bónus para utilização
			</div>

			<div class="table_user mini-mbottom">
				<table class="col-xs-12">
					<thead>
						<tr>
							<th class="col-xs-2 acenter">Origem</th>
							<th class="col-xs-4 acenter">Tipo</th>
							<th class="col-xs-2 acenter">Bónus</th>
							<th class="col-xs-2 acenter">Código</th>
							<th class="col-xs-2 acenter">Opções</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td class="col-xs-2 acenter">Desporto</td>
							<td class="col-xs-4 acenter">1º Depósito <li class="fa fa-info-circle brand-color"></li></td>
							<td class="col-xs-2 acenter">200%</td>
							<td class="col-xs-2 acenter success-color"><b>FREEBET</b></td>
							<td class="col-xs-2 acenter neut-back"><div class="brand-botao brand-botao-mini brand-link">Resgatar</div></td>
						</tr>
						<tr>
							<td class="col-xs-2 acenter">Desporto</td>
							<td class="col-xs-4 acenter">Bónus Fidelidade <li class="fa fa-info-circle brand-color"></li></td>
							<td class="col-xs-2 acenter">50%</td>
							<td class="col-xs-2 acenter success-color"><b>IBETUP15</b></td>
							<td class="col-xs-2 acenter neut-back"><div class="brand-botao brand-botao-mini brand-link">Resgatar</div></td>
						</tr>
						<tr>
							<td class="col-xs-2 acenter">Casino</td>
							<td class="col-xs-4 acenter">1º Depósito <li class="fa fa-info-circle brand-color"></li></td>
							<td class="col-xs-2 acenter">200%</td>
							<td class="col-xs-2 acenter success-color"><b>FREEBET</b></td>
							<td class="col-xs-2 acenter neut-back"><div class="brand-botao brand-botao-mini brand-link">Resgatar</div></td>
						</tr>
						<tr>
							<td class="col-xs-2 acenter">Póker</td>
							<td class="col-xs-4 acenter">1º Depósito <li class="fa fa-info-circle brand-color"></li></td>
							<td class="col-xs-2 acenter">200%</td>
							<td class="col-xs-2 acenter success-color"><b>FREEBET</b></td>
							<td class="col-xs-2 acenter neut-back"><div class="brand-botao brand-botao-mini brand-link">Resgatar</div></td>
						</tr>
						<tr>
							<td class="col-xs-2 acenter">Jogos/Vegas</td>
							<td class="col-xs-4 acenter">1º Depósito <li class="fa fa-info-circle brand-color"></li></td>
							<td class="col-xs-2 acenter">200%</td>
							<td class="col-xs-2 acenter success-color"><b>FREEBET</b></td>
							<td class="col-xs-2 acenter neut-back"><div class="brand-botao brand-botao-mini brand-link">Resgatar</div></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="clear"></div>
@stop

@section('scripts')


@stop