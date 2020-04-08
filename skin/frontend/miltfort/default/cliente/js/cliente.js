jQuery(function(){
	//mascaras
	mascaraTaxvat();
	jQuery('.zip input').mask('99999-999');
	jQuery('.telephone input').mask('(99)9999-9999?9');
	jQuery('.fax input').mask('(99)9999-9999?9');
	jQuery('.shipping-form #postcode').mask('99999-999');
	

	//events
	jQuery('.tipo input').click(function(){
		carregaTipo(jQuery(this));
	});
	/*jQuery('.zip input').keyup(function(){
		if(jQuery(this).val().replace(/_/g,'').length >= 9){
			buscaEndereco(jQuery(this));			
		}
	});*/
	/*jQuery('.zip input').focusout(function(){
		var scope = jQuery(this).parents('form:first');
		if(jQuery(this).val().replace(/_/g,'').length < 9){
			scope.find(".street-address input").val('');
			scope.find(".bairro input").val('');
			scope.find(".city input").val('');
			scope.find(".region select").val('');
		}		
	});*/
	
});

function carregaTipo(e){
	var scope = e.parents('form:first');	
	var tipo = scope.find('.tipo input:checked').val();
	if(tipo == 2){
		scope.find('.personal-information .legend').html('Dados da Empresa');
		scope.find('.name-firstname label').html('<em>*</em>Nome Fantasia');
		scope.find('.name-lastname label').html('<em>*</em>Raz&atilde;o Social');
		scope.find('.taxvat label').html('<em>*</em>CNPJ');
		scope.find('.rgie label').html('<em>*</em>IE');
		scope.find('.address-information .legend').html('Endere&ccedil;o da Empresa');
	}else{
		scope.find('.personal-information .legend').html('Dados Pessoais');
		scope.find('.name-firstname label').html('<em>*</em>Nome');
		scope.find('.name-lastname label').html('<em>*</em>Sobrenome');
		scope.find('.taxvat label').html('<em>*</em>CPF');
		scope.find('.rgie label').html('<em>*</em>RG');
		scope.find('.address-information .legend').html('Seu endere&ccedil;o');
	}
	mascaraTaxvat();
}

function mascaraTaxvat(){
	var tipo = jQuery('.tipo input:checked').val();
	if(tipo == 2){		
		jQuery('.taxvat input').mask('99.999.999/9999-99');		
	}else{
		jQuery('.taxvat input').mask('999.999.999-99');		
	}
}

/*function buscaEndereco(e) {
	if(jQuery.trim(e.val()) != ""){
		jQuery.getScript("http://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep="+e.val(), function(){
			var scope = e.parents('form:first');
	 		if(resultadoCEP["resultado"]){
					if(unescape(resultadoCEP["logradouro"])){
					scope.find(".street-address input").val(unescape(resultadoCEP["tipo_logradouro"])+" "+unescape(resultadoCEP["logradouro"]));						
					}
					if(unescape(resultadoCEP["bairro"])){
					scope.find(".bairro input").val(unescape(resultadoCEP["bairro"]));						
					}
					scope.find(".city input").val(unescape(resultadoCEP["cidade"]));
					scope.find(".region select").val(globalRegions[unescape(resultadoCEP["uf"])]);
			}else{
					scope.find(".street-address input").val('');
					scope.find(".bairro input").val('');
					scope.find(".city input").val('');
					scope.find(".region select").val('');
			}
		});
	}
}*/

function validaTaxvat(cpf,pType){
	var cpf_filtrado = "", valor_1 = " ", valor_2 = " ", ch = "";
	var valido = false;
	for (i = 0; i < cpf.length; i++){
		ch = cpf.substring(i, i + 1);
		if (ch >= "0" && ch <= "9"){
			cpf_filtrado = cpf_filtrado.toString() + ch.toString()
			valor_1 = valor_2;
			valor_2 = ch;
		}
		if ((valor_1 != " ") && (!valido)) valido = !(valor_1 == valor_2);
	}
	if (!valido) cpf_filtrado = "12345678912";
	if (cpf_filtrado.length < 11){
		for (i = 1; i <= (11 - cpf_filtrado.length); i++){cpf_filtrado = "0" + cpf_filtrado;}
	}
	if(pType <= 1){
		if ( ( cpf_filtrado.substring(9,11) == checkCPF( cpf_filtrado.substring(0,9) ) ) && ( cpf_filtrado.substring(11,12)=="") ){return true;}
	}
	if((pType == 2) || (pType == 0)){
		if (cpf_filtrado.length >= 14){
			if ( cpf_filtrado.substring(12,14) == checkCNPJ( cpf_filtrado.substring(0,12) ) ){ return true;}
		}
	}
	return false;
}

function checkCNPJ(vCNPJ){
	var mControle = "";
	var aTabCNPJ = new Array(5,4,3,2,9,8,7,6,5,4,3,2);
	for (i = 1 ; i <= 2 ; i++){
		mSoma = 0;
		for (j = 0 ; j < vCNPJ.length ; j++)
			mSoma = mSoma + (vCNPJ.substring(j,j+1) * aTabCNPJ[j]);
		if (i == 2 ) mSoma = mSoma + ( 2 * mDigito );
		mDigito = ( mSoma * 10 ) % 11;
		if (mDigito == 10 ) mDigito = 0;
		mControle1 = mControle ;
		mControle = mDigito;
		aTabCNPJ = new Array(6,5,4,3,2,9,8,7,6,5,4,3);
	}
	return( (mControle1 * 10) + mControle );
}

function checkCPF(vCPF){
	var mControle = ""
	var mContIni = 2, mContFim = 10, mDigito = 0;
	for (j = 1 ; j <= 2 ; j++){
		mSoma = 0;
		for (i = mContIni ; i <= mContFim ; i++)
		mSoma = mSoma + (vCPF.substring((i-j-1),(i-j)) * (mContFim + 1 + j - i));
		if (j == 2 ) mSoma = mSoma + ( 2 * mDigito );
		mDigito = ( mSoma * 10 ) % 11;
		if (mDigito == 10) mDigito = 0;
		mControle1 = mControle;
		mControle = mDigito;
		mContIni = 3;
		mContFim = 11;
	}
	return( (mControle1 * 10) + mControle );
}


jQuery('body, html').delegate( 'div.field.zip input', 'keyup', function(){
	if( jQuery(this).val().replace('-','').replace('_','').length == 8 ) consultarCep(this);
	//else console.log(jQuery(this).val().replace('-','').replace('_','').length);
} );

/**
 * Clean error messages from fields
 */
/*function validateAddress(data){
	if( data.cidade == '[object Object]' ) data.cidade = '';
	if( data.tipo_logradouro == '[object Object]' ) data.tipo_logradouro = '';
	if( data.logradouro == '[object Object]' ) data.logradouro = '';
	if( data.bairro == '[object Object]' ) data.bairro = '';
	if( data.uf == '[object Object]' ) data.uf = '';
	
	return data;
}*/
/**
 * Clean error messages from fields
 */
function validateAddress(data){
	if( data.cidade == '[object Object]' ) data.cidade = '';
	if( data.tipo_logradouro == '[object Object]' ) data.tipo_logradouro = '';
	if( data.logradouro == '[object Object]' ) data.logradouro = '';
	if( data.bairro == '[object Object]' ) data.bairro = '';
	// if( data.uf == '[object Object]' ) data.uf = '';
	if( data.estado == '[object Object]' ) data.estado = '';
	else{
		var region = '';
		// switch( data.uf ){
		switch( data.estado ){
			case 'AC':
				region = 'Acre';
				break;
			case 'AL':
				region = 'Alagoas';
				break;
			case 'AP':
				region = 'Amapá';
				break;
			case 'AM':
				region = 'Amazonas';
				break;
			case 'BA':
				region = 'Bahia';
				break;
			case 'CE':
				region = 'Ceará';
				break;
			case 'DF':
				region = 'Distrito Federal';
				break;
			case 'ES':
				region = 'Espírito Santo';
				break;
			case 'GO':
				region = 'Goiás';
				break;
			case 'MA':
				region = 'Maranhão';
				break;
			case 'MT':
				region = 'Mato Grosso';
				break;
			case 'MS':
				region = 'Mato Grosso do Sul';
				break;
			case 'MG':
				region = 'Minas Gerais';
				break;
			case 'PA':
				region = 'Pará';
				break;
			case 'PB':
				region = 'Paraíba';
				break;
			case 'PR':
				region = 'Paraná';
				break;
			case 'PE':
				region = 'Pernambuco';
				break;
			case 'PI':
				region = 'Piauí';
				break;
			case 'RJ':
				region = 'Rio de Janeiro';
				break;
			case 'RN':
				region = 'Rio Grande do Norte';
				break;
			case 'RS':
				region = 'Rio Grande do Sul';
				break;
			case 'RR':
				region = 'Roraima';
				break;
			case 'SC':
				region = 'Santa Catarina';
				break;
			case 'SP':
				region = 'São Paulo';
				break;
			case 'SE':
				region = 'Sergipe';
				break;
			case 'TO':
				region = 'Tocantins';
				break;
		}
		
		data.region = region;
	}
	
	return data;
}



/**
 *
 */
function consultarCep(obj){
	
	var target = jQuery('#'+jQuery(obj).attr('data-target-id'));
	var baseUrl = window.location.protocol + '//' + window.location.hostname + '/cliente/cep/consultar/cep/';
	var cep = target.find('div.field.zip input').val().replace('-','');	
	
	var url = baseUrl + cep;
	
	target.find('div.field.street-address input').val('Carregando...').attr('disabled','disabled').addClass('loading-field');
	target.find('div.field.bairro input').val('Carregando...').attr('disabled','disabled').addClass('loading-field');
	target.find('div.field.city input').val('Carregando...').attr('disabled','disabled').addClass('loading-field');
	// target.find('div.field.region input').val('Carregando...').attr('disabled','disabled').addClass('loading-field');
	target.find('div.field.region select').attr('disabled','disabled').addClass('loading-field').prepend('<option value="">Carregando...</option>').find('option').first().attr('selected','selected')	;
	
	jQuery.getJSON(url, function(data){
		// console.log(data.cidade != '[object Object]');
		if( data.cidade != '[object Object]' ){
			// console.log(data);
			data = validateAddress(data);
			// console.log('------------------');
			// console.log(data);
			// console.log('------------------');
			
			if(data.bairro == undefined){
				data.bairro = '';
			}
			
			if(data.logradouro != undefined){
				var fullStreet = data.logradouro.length > 0 ? data.logradouro : '';
			}else{
				var fullStreet = '';
			}
			
			target.find('div.field.street-address input').val(unescape(fullStreet)).removeClass('loading-field').removeAttr('disabled');
			target.find('div.field.bairro input').val(unescape(data.bairro)).removeClass('loading-field').removeAttr('disabled');
			target.find('div.field.city input').val(unescape(data.cidade)).removeClass('loading-field').removeAttr('disabled');
			// target.find('div.field.region input').val(unescape(data.uf)).removeClass('loading-field').removeAttr('disabled');
			target.find('div.field.region select option').each(function(){
				if( this.title == data.region ) jQuery(this).attr('selected','selected');
			});
			target.find('div.field.region select').removeClass('loading-field').removeAttr('disabled').find('option').first().remove();
			
			/*
			//republica
			
			var fullStreet = data.tipo_logradouro.length > 0 && data.logradouro.length > 0 ? data.tipo_logradouro + ' ' + data.logradouro : '';
			target.find('div.field.street-address input').val(unescape(fullStreet)).removeClass('loading-field').removeAttr('disabled');
			target.find('div.field.bairro input').val(unescape(data.bairro)).removeClass('loading-field').removeAttr('disabled');
			target.find('div.field.city input').val(unescape(data.cidade)).removeClass('loading-field').removeAttr('disabled');
			// target.find('div.field.region input').val(unescape(data.uf)).removeClass('loading-field').removeAttr('disabled');
			target.find('div.field.region select option').each(function(){
				if( this.title == data.region ) jQuery(this).attr('selected','selected');
			});
			target.find('div.field.region select').removeClass('loading-field').removeAttr('disabled').find('option').first().remove();
			*/
		}else{
			target.find('div.field.street-address input').val('').removeClass('loading-field').removeAttr('disabled');
			target.find('div.field.bairro input').val('').removeClass('loading-field').removeAttr('disabled');
			target.find('div.field.city input').val('').removeClass('loading-field').removeAttr('disabled');
			// target.find('div.field.region input').val('').removeClass('loading-field').removeAttr('disabled');
			target.find('div.field.region select').removeClass('loading-field').removeAttr('disabled').find('option').first().remove();
		}
	});
}

jQuery('document').ready(function(){
	jQuery('[name="tipo"]:checked').click();
})