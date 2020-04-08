﻿<?php

$installer = $this;

$installer->startSetup();

$installer->run('
	INSERT INTO '.$installer->getTable('jadlogmethod/cep').'( estado, localidade, cep_inicial, cep_final, standard, rodoviario, package, economico, doc, corporate, com, internacional, cargo, emergencia ) VALUES("RS","Colorado","99460000","99469999","3","3", "", "", "", "", "", "", "", ""),
		("RS","Não-Me-Toque","99470000","99489999","2","3", "", "", "", "", "", "", "", ""),
		("RS","Tapera","99490000","99494999","3","3", "", "", "", "", "", "", "", ""),
		("RS","Lagoa dos Três Cantos","99495000","99499999","3","3", "", "", "", "", "", "", "", ""),
		("RS","Carazinho","99500000","99522999","1","2", "", "", "", "", "", "", "", ""),
		("RS","Almirante Tamandaré do Sul","99523000","99524999","3","3", "", "", "", "", "", "", "", ""),
		("RS","Santo Antônio do Planalto","99525000","99527999","3","3", "", "", "", "", "", "", "", ""),
		("RS","Coqueiros do Sul","99528000","99529999","3","3", "", "", "", "", "", "", "", ""),
		("RS","Chapada","99530000","99559999","3","3", "", "", "", "", "", "", "", ""),
		("RS","Sarandi","99560000","99579999","3","3", "", "", "", "", "", "", "", ""),
		("RS","Nova Boa Vista","99580000","99584999","3","3", "", "", "", "", "", "", "", ""),
		("RS","Barra Funda","99585000","99589999","3","3", "", "", "", "", "", "", "", ""),
		("RS","Rondinha","99590000","99599999","3","3", "", "", "", "", "", "", "", ""),
		("RS","Nonoai","99600000","99604999","3","3", "", "", "", "", "", "", "", ""),
		("RS","Gramado dos Loureiros","99605000","99609999","3","3", "", "", "", "", "", "", "", ""),
		("RS","Rio dos Índios","99610000","99614999","4","5", "", "", "", "", "", "", "", ""),
		("RS","Trindade do Sul","99615000","99639999","3","3", "", "", "", "", "", "", "", ""),
		("RS","São Valentim","99640000","99644999","6","7", "", "", "", "", "", "", "", ""),
		("RS","Entre Rios do Sul","99645000","99649999","6","7", "", "", "", "", "", "", "", ""),
		("RS","Benjamin Constant do Sul","99650000","99654999","3","4", "", "", "", "", "", "", "", ""),
		("RS","Faxinalzinho","99655000","99659999","6","7", "", "", "", "", "", "", "", ""),
		("RS","Campinas do Sul","99660000","99664999","4","5", "", "", "", "", "", "", "", ""),
		("RS","Cruzaltense","99665000","99669999","6","7", "", "", "", "", "", "", "", ""),
		("RS","Ronda Alta","99670000","99674999","3","3", "", "", "", "", "", "", "", ""),
		("RS","Três Palmeiras","99675000","99677999","3","3", "", "", "", "", "", "", "", ""),
		("RS","Progresso (Três Palmeiras)","99678000","99679999","4","5", "", "", "", "", "", "", "", ""),
		("RS","Constantina","99680000","99686999","3","3", "", "", "", "", "", "", "", ""),
		("RS","Novo Xingu","99687000","99689999","3","3", "", "", "", "", "", "", "", ""),
		("RS","Liberato Salzano","99690000","99697999","3","3", "", "", "", "", "", "", "", ""),
		("RS","Engenho Velho","99698000","99699999","3","3", "", "", "", "", "", "", "", ""),
		("RS","Erechim","99700000","99717999","1","2", "", "", "", "", "", "", "", ""),
		("RS","Paulo Bento","99718000","99719999","2","2", "", "", "", "", "", "", "", ""),
		("RS","Quatro Irmãos","99720000","99724999","4","5", "", "", "", "", "", "", "", ""),
		("RS","Três Arroios","99725000","99729999","4","5", "", "", "", "", "", "", "", ""),
		("RS","Jacutinga","99730000","99734999","4","5", "", "", "", "", "", "", "", ""),
		("RS","Ponte Preta","99735000","99739999","6","7", "", "", "", "", "", "", "", ""),
		("RS","Barão de Cotegipe","99740000","99749999","1","2", "", "", "", "", "", "", "", ""),
		("RS","Erval Grande","99750000","99759999","6","7", "", "", "", "", "", "", "", ""),
		("RS","Itatiba do Sul","99760000","99769999","6","7", "", "", "", "", "", "", "", ""),
		("RS","Aratiba","99770000","99789999","2","3", "", "", "", "", "", "", "", ""),
		("RS","Mariano Moro","99790000","99794999","4","5", "", "", "", "", "", "", "", ""),
		("RS","Barra do Rio Azul","99795000","99799999","3","4", "", "", "", "", "", "", "", ""),
		("RS","Marcelino Ramos","99800000","99809999","3","4", "", "", "", "", "", "", "", ""),
		("RS","Severiano de Almeida","99810000","99819999","3","4", "", "", "", "", "", "", "", ""),
		("RS","Viadutos","99820000","99824999","4","5", "", "", "", "", "", "", "", ""),
		("RS","Carlos Gomes","99825000","99829999","4","5", "", "", "", "", "", "", "", ""),
		("RS","Gaurama","99830000","99834999","2","2", "", "", "", "", "", "", "", ""),
		("RS","Áurea","99835000","99837999","4","5", "", "", "", "", "", "", "", ""),
		("RS","Centenário","99838000","99839999","6","7", "", "", "", "", "", "", "", ""),
		("RS","Sananduva","99840000","99849999","4","5", "", "", "", "", "", "", "", ""),
		("RS","Paim Filho","99850000","99854999","6","7", "", "", "", "", "", "", "", ""),
		("RS","São João da Urtiga","99855000","99859999","6","7", "", "", "", "", "", "", "", ""),
		("RS","Cacique Doble","99860000","99869999","6","7", "", "", "", "", "", "", "", ""),
		("RS","São José do Ouro","99870000","99877999","6","7", "", "", "", "", "", "", "", ""),
		("RS","Tupanci do Sul","99878000","99879999","4","5", "", "", "", "", "", "", "", ""),
		("RS","Machadinho","99880000","99889999","6","7", "", "", "", "", "", "", "", ""),
		("RS","Maximiliano de Almeida","99890000","99894999","6","7", "", "", "", "", "", "", "", ""),
		("RS","Santo Expedito do Sul","99895000","99899999","4","5", "", "", "", "", "", "", "", ""),
		("RS","Getúlio Vargas","99900000","99909999","2","2", "", "", "", "", "", "", "", ""),
		("RS","Floriano Peixoto","99910000","99919999","6","7", "", "", "", "", "", "", "", ""),
		("RS","Erebango","99920000","99924999","4","5", "", "", "", "", "", "", "", ""),
		("RS","Ipiranga do Sul","99925000","99929999","6","7", "", "", "", "", "", "", "", ""),
		("RS","Estação","99930000","99939999","1","2", "", "", "", "", "", "", "", ""),
		("RS","Ibiaçá","99940000","99949999","4","5", "", "", "", "", "", "", "", ""),
		("RS","Tapejara","99950000","99951999","4","5", "", "", "", "", "", "", "", ""),
		("RS","Santa Cecília do Sul","99952000","99954999","4","5", "", "", "", "", "", "", "", ""),
		("RS","Vila Lângaro","99955000","99959999","6","7", "", "", "", "", "", "", "", ""),
		("RS","Charrua","99960000","99964999","6","7", "", "", "", "", "", "", "", ""),
		("RS","Água Santa","99965000","99969999","6","7", "", "", "", "", "", "", "", ""),
		("RS","Ciríaco","99970000","99979999","4","5", "", "", "", "", "", "", "", ""),
		("RS","David Canabarro","99980000","99989999","4","5", "", "", "", "", "", "", "", ""),
		("RS","Muliterno","99990000","99999999","4","5", "", "", "", "", "", "", "", "");
');

$installer->endSetup();