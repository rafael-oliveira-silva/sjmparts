﻿<?php

$installer = $this;

$installer->startSetup();

$installer->run('
	INSERT INTO '.$installer->getTable('jadlogmethod/cep').'( estado, localidade, cep_inicial, cep_final, standard, rodoviario, package, economico, doc, corporate, com, internacional, cargo, emergencia ) VALUES("PR","Curitiba","81500000","81799999","1","2", "", "", "", "", "", "", "", ""),
		("PR","Curitiba","81800000","81999999","1","2", "", "", "", "", "", "", "", ""),
		("PR","Curitiba","82000000","82099999","1","2", "", "", "", "", "", "", "", ""),
		("PR","Curitiba","82100000","82299999","1","2", "", "", "", "", "", "", "", ""),
		("PR","Curitiba","82300000","82499999","1","2", "", "", "", "", "", "", "", ""),
		("PR","Curitiba","82500000","82999999","1","2", "", "", "", "", "", "", "", ""),
		("PR","São José dos Pinhais","83000000","83149999","1","2", "", "", "", "", "", "", "", ""),
		("PR","Tijucas do Sul","83190000","83199999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Paranaguá","83200000","83249999","2","2", "", "", "", "", "", "", "", ""),
		("PR","Pontal do Paraná","83255000","83259999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Matinhos","83260000","83279999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Guaratuba","83280000","83299999","5","6", "", "", "", "", "", "", "", ""),
		("PR","Piraquara","83300000","83318999","7","8", "", "", "", "", "", "", "", ""),
		("PR","Pinhais","83320000","83349999","1","2", "", "", "", "", "", "", "", ""),
		("PR","Morretes","83350000","83369999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Antonina","83370000","83389999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Guaraqueçaba","83390000","83399999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Colombo","83400000","83415999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Quatro Barras","83420000","83429999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Campina Grande do Sul","83430000","83449999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Bocaiúva do Sul","83450000","83479999","5","6", "", "", "", "", "", "", "", ""),
		("PR","Tunas do Paraná","83480000","83489999","6","7", "", "", "", "", "", "", "", ""),
		("PR","Adrianópolis","83490000","83499999","5","6", "", "", "", "", "", "", "", ""),
		("PR","Almirante Tamandaré","83500000","83529999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Campo Magro","83535000","83539999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Rio Branco do Sul","83540000","83559999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Itaperuçu","83560000","83569999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Cerro Azul","83570000","83589999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Doutor Ulysses","83590000","83599999","6","7", "", "", "", "", "", "", "", ""),
		("PR","Campo Largo","83600000","83640999","1","2", "", "", "", "", "", "", "", ""),
		("PR","Balsa Nova","83650000","83699999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Araucária","83700000","83724999","2","2", "", "", "", "", "", "", "", ""),
		("PR","Contenda","83730000","83749999","5","6", "", "", "", "", "", "", "", ""),
		("PR","Lapa","83750000","83799999","5","6", "", "", "", "", "", "", "", ""),
		("PR","Mandirituba","83800000","83819999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Fazenda Rio Grande","83820000","83839999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Quitandinha","83840000","83849999","5","6", "", "", "", "", "", "", "", ""),
		("PR","Agudos do Sul","83850000","83859999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Piên","83860000","83869999","2","2", "", "", "", "", "", "", "", ""),
		("PR","Campo do Tenente","83870000","83879999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Rio Negro","83880000","83899999","3","4", "", "", "", "", "", "", "", ""),
		("PR","São Mateus do Sul","83900000","83979999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Antônio Olinto","83980000","83999999","5","6", "", "", "", "", "", "", "", ""),
		("PR","Ponta Grossa","84000000","84099999","1","2", "", "", "", "", "", "", "", ""),
		("PR","Palmeira","84130000","84139999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Porto Amazonas","84140000","84144999","6","7", "", "", "", "", "", "", "", ""),
		("PR","Carambeí","84145000","84149999","1","2", "", "", "", "", "", "", "", ""),
		("PR","São João do Triunfo","84150000","84159999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Castro","84160000","84184999","2","3", "", "", "", "", "", "", "", ""),
		("PR","Jaguariaíva","84200000","84219999","6","7", "", "", "", "", "", "", "", ""),
		("PR","Sengés","84220000","84239999","6","7", "", "", "", "", "", "", "", ""),
		("PR","Piraí do Sul","84240000","84249999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Imbaú","84250000","84259999","5","6", "", "", "", "", "", "", "", ""),
		("PR","Telêmaco Borba","84260000","84274999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Curiúva","84280000","84284999","5","6", "", "", "", "", "", "", "", ""),
		("PR","Figueira","84285000","84289999","7","8", "", "", "", "", "", "", "", ""),
		("PR","Sapopema","84290000","84299999","6","7", "", "", "", "", "", "", "", ""),
		("PR","Tibagi","84300000","84319999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Reserva","84320000","84344999","5","6", "", "", "", "", "", "", "", ""),
		("PR","Ventania","84345000","84349999","5","6", "", "", "", "", "", "", "", ""),
		("PR","Ortigueira","84350000","84399999","5","6", "", "", "", "", "", "", "", ""),
		("PR","Prudentópolis","84400000","84429999","5","6", "", "", "", "", "", "", "", ""),
		("PR","Imbituva","84430000","84434999","5","6", "", "", "", "", "", "", "", ""),
		("PR","Guamiranga","84435000","84449999","5","6", "", "", "", "", "", "", "", ""),
		("PR","Ipiranga","84450000","84459999","5","6", "", "", "", "", "", "", "", ""),
		("PR","Ivaí","84460000","84469999","5","6", "", "", "", "", "", "", "", ""),
		("PR","Cândido de Abreu","84470000","84499999","5","6", "", "", "", "", "", "", "", ""),
		("PR","Irati","84500000","84529999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Teixeira Soares","84530000","84534999","6","7", "", "", "", "", "", "", "", ""),
		("PR","Fernandes Pinheiro","84535000","84549999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Rebouças","84550000","84559999","5","6", "", "", "", "", "", "", "", ""),
		("PR","Rio Azul","84560000","84569999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Mallet","84570000","84599999","3","4", "", "", "", "", "", "", "", ""),
		("PR","União da Vitória","84600000","84607999","2","2", "", "", "", "", "", "", "", ""),
		("PR","São Domingos (União da Vitória)","84608000","84609999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Porto Vitória","84610000","84619999","2","3", "", "", "", "", "", "", "", ""),
		("PR","Cruz Machado","84620000","84629999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Paula Freitas","84630000","84634999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Paulo Frontin","84635000","84639999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Bituruna","84640000","84659999","3","4", "", "", "", "", "", "", "", ""),
		("PR","General Carneiro","84660000","84899999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Ibaiti","84900000","84919999","7","8", "", "", "", "", "", "", "", ""),
		("PR","Japira","84920000","84924999","7","8", "", "", "", "", "", "", "", ""),
		("PR","Pinhalão","84925000","84929999","6","7", "", "", "", "", "", "", "", ""),
		("PR","Jaboti","84930000","84934999","7","8", "", "", "", "", "", "", "", ""),
		("PR","Tomazina","84935000","84939999","7","8", "", "", "", "", "", "", "", ""),
		("PR","Siqueira Campos","84940000","84944999","7","8", "", "", "", "", "", "", "", ""),
		("PR","Salto do Itararé","84945000","84949999","5","6", "", "", "", "", "", "", "", ""),
		("PR","Wenceslau Braz","84950000","84969999","6","7", "", "", "", "", "", "", "", ""),
		("PR","Santana do Itararé","84970000","84979999","7","8", "", "", "", "", "", "", "", ""),
		("PR","São José da Boa Vista","84980000","84989999","6","7", "", "", "", "", "", "", "", ""),
		("PR","Arapoti","84990000","84999999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Guarapuava","85000000","85099999","2","3", "", "", "", "", "", "", "", ""),
		("PR","Colônia Socorro (Guarapuava)","85139000","85139999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Candói","85140000","85144999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Foz do Jordão","85145000","85147999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Campina do Simão","85148000","85149999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Turvo","85150000","85154999","8","9", "", "", "", "", "", "", "", ""),
		("PR","Inácio Martins","85155000","85159999","8","9", "", "", "", "", "", "", "", ""),
		("PR","Cantagalo","85160000","85161999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Goioxim","85162000","85167999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Marquinho","85168000","85169999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Pinhão","85170000","85194999","5","6", "", "", "", "", "", "", "", ""),
		("PR","Reserva do Iguaçu","85195000","85199999","6","7", "", "", "", "", "", "", "", ""),
		("PR","Pitanga","85200000","85224999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Boa Ventura de São Roque","85225000","85229999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Santa Maria do Oeste","85230000","85239999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Mato Rico","85240000","85249999","7","9", "", "", "", "", "", "", "", ""),
		("PR","Nova Tebas","85250000","85259999","7","9", "", "", "", "", "", "", "", ""),
		("PR","Manoel Ribas","85260000","85269999","7","9", "", "", "", "", "", "", "", ""),
		("PR","Palmital","85270000","85274999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Laranjal","85275000","85279999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Altamira do Paraná","85280000","85299999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Laranjeiras do Sul","85300000","85319999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Rio Bonito do Iguaçu","85340000","85344999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Porto Barreiro","85345000","85349999","6","7", "", "", "", "", "", "", "", ""),
		("PR","Nova Laranjeiras","85350000","85389999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Virmond","85390000","85399999","6","7", "", "", "", "", "", "", "", ""),
		("PR","Guaraniaçu","85400000","85407999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Diamante do Sul","85408000","85409999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Nova Aurora","85410000","85414999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Cafelândia","85415000","85419999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Corbélia","85420000","85422999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Iguatu","85423000","85424999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Anahy","85425000","85429999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Braganey","85430000","85439999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Ubiratã","85440000","85449999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Campo Bonito","85450000","85459999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Quedas do Iguaçu","85460000","85464999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Espigão Alto do Iguaçu","85465000","85469999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Catanduvas","85470000","85477999","8","10", "", "", "", "", "", "", "", ""),
		("PR","Ibema","85478000","85484999","4","7", "", "", "", "", "", "", "", ""),
		("PR","Três Barras do Paraná","85485000","85499999","4","7", "", "", "", "", "", "", "", ""),
		("PR","Pato Branco","85500000","85513999","2","4", "", "", "", "", "", "", "", ""),
		("PR","Bom Sucesso do Sul","85515000","85519999","4","7", "", "", "", "", "", "", "", ""),
		("PR","Vitorino","85520000","85524999","4","7", "", "", "", "", "", "", "", ""),
		("PR","Mariópolis","85525000","85529999","3","6", "", "", "", "", "", "", "", ""),
		("PR","Clevelândia","85530000","85539999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Mangueirinha","85540000","85547999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Honório Serpa","85548000","85549999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Coronel Vivida","85550000","85554999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Palmas","85555000","85556999","8","9", "", "", "", "", "", "", "", ""),
		("PR","Coronel Domingos Soares","85557000","85559999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Chopinzinho","85560000","85564999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Sulina","85565000","85567999","4","7", "", "", "", "", "", "", "", ""),
		("PR","Saudade do Iguaçu","85568000","85569999","4","5", "", "", "", "", "", "", "", ""),
		("PR","São João","85570000","85574999","4","7", "", "", "", "", "", "", "", ""),
		("PR","São Jorge D\'Oeste","85575000","85579999","4","7", "", "", "", "", "", "", "", ""),
		("PR","Itapejara d\'Oeste","85580000","85584999","3","6", "", "", "", "", "", "", "", ""),
		("PR","Verê","85585000","85597999","4","7", "", "", "", "", "", "", "", ""),
		("PR","Cruzeiro do Iguaçu","85598000","85599999","4","7", "", "", "", "", "", "", "", ""),
		("PR","Francisco Beltrão","85600000","85606999","2","4", "", "", "", "", "", "", "", ""),
		("PR","Renascença","85610000","85614999","4","7", "", "", "", "", "", "", "", ""),
		("PR","Marmeleiro","85615000","85617999","2","4", "", "", "", "", "", "", "", ""),
		("PR","Flor da Serra do Sul","85618000","85619999","4","7", "", "", "", "", "", "", "", ""),
		("PR","Salgado Filho","85620000","85627999","4","7", "", "", "", "", "", "", "", ""),
		("PR","Manfrinópolis","85628000","85629999","4","7", "", "", "", "", "", "", "", ""),
		("PR","Enéas Marques","85630000","85634999","4","7", "", "", "", "", "", "", "", ""),
		("PR","Nova Esperança do Sudoeste","85635000","85639999","4","7", "", "", "", "", "", "", "", ""),
		("PR","Ampére","85640000","85649999","4","7", "", "", "", "", "", "", "", ""),
		("PR","Santa Izabel do Oeste","85650000","85659999","4","7", "", "", "", "", "", "", "", ""),
		("PR","Dois Vizinhos","85660000","85669999","8","11", "", "", "", "", "", "", "", ""),
		("PR","Salto do Lontra","85670000","85679999","4","7", "", "", "", "", "", "", "", ""),
		("PR","Boa Esperança do Iguaçu","85680000","85684999","4","7", "", "", "", "", "", "", "", ""),
		("PR","Nova Prata do Iguaçu","85685000","85699999","4","7", "", "", "", "", "", "", "", ""),
		("PR","Barracão","85700000","85707999","4","7", "", "", "", "", "", "", "", ""),
		("PR","Bom Jesus do Sul","85708000","85709999","4","7", "", "", "", "", "", "", "", ""),
		("PR","Santo Antônio do Sudoeste","85710000","85726999","4","7", "", "", "", "", "", "", "", ""),
		("PR","Pinhal de São Bento","85727000","85729999","4","7", "", "", "", "", "", "", "", ""),
		("PR","Pranchita","85730000","85739999","5","7", "", "", "", "", "", "", "", ""),
		("PR","Pérola d\'Oeste","85740000","85744999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Bela Vista da Caroba","85745000","85749999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Planalto","85750000","85759999","5","7", "", "", "", "", "", "", "", ""),
		("PR","Capanema","85760000","85769999","5","7", "", "", "", "", "", "", "", ""),
		("PR","Realeza","85770000","85779999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Boa Vista da Aparecida","85780000","85789999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Capitão Leônidas Marques","85790000","85794999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Santa Lúcia","85795000","85799999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Cascavel","85800000","85820999","2","3", "", "", "", "", "", "", "", ""),
		("PR","São João d\'Oeste (Cascavel)","85823000","85824999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Santa Tereza do Oeste","85825000","85825999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Lindoeste","85826000","85829999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Formosa do Oeste","85830000","85832999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Iracema do Oeste","85833000","85834999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Jesuítas","85835000","85839999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Céu Azul","85840000","85844999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Vera Cruz do Oeste","85845000","85849999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Foz do Iguaçu","85850000","85871999","2","3", "", "", "", "", "", "", "", ""),
		("PR","Santa Terezinha de Itaipu","85875000","85876999","5","7", "", "", "", "", "", "", "", ""),
		("PR","São Miguel do Iguaçu","85877000","85879999","5","7", "", "", "", "", "", "", "", ""),
		("PR","Itaipulândia","85880000","85883999","9","11", "", "", "", "", "", "", "", ""),
		("PR","Medianeira","85884000","85884999","5","7", "", "", "", "", "", "", "", ""),
		("PR","Serranópolis do Iguaçu","85885000","85886999","6","8", "", "", "", "", "", "", "", ""),
		("PR","Matelândia","85887000","85887999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Ramilândia","85888000","85889999","5","7", "", "", "", "", "", "", "", ""),
		("PR","Missal","85890000","85891999","5","7", "", "", "", "", "", "", "", ""),
		("PR","Santa Helena","85892000","85895999","5","7", "", "", "", "", "", "", "", ""),
		("PR","Diamante d\'Oeste","85896000","85897999","4","6", "", "", "", "", "", "", "", ""),
		("PR","São José das Palmeiras","85898000","85899999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Toledo","85900000","85919999","2","3", "", "", "", "", "", "", "", ""),
		("PR","São Pedro do Iguaçu","85929000","85929999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Nova Santa Rosa","85930000","85932999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Ouro Verde do Oeste","85933000","85934999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Assis Chateaubriand","85935000","85939999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Quatro Pontes","85940000","85944999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Tupãssi","85945000","85947999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Pato Bragado","85948000","85949999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Palotina","85950000","85954999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Maripá","85955000","85959999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Marechal Cândido Rondon","85960000","85979999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Guaíra","85980000","85987999","5","6", "", "", "", "", "", "", "", ""),
		("PR","Entre Rios do Oeste","85988000","85989999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Terra Roxa","85990000","85997999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Mercedes","85998000","85999999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Londrina","86000000","86099999","1","2", "", "", "", "", "", "", "", ""),
		("PR","Tamarana","86125000","86129999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Bela Vista do Paraíso","86130000","86139999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Primeiro de Maio","86140000","86149999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Alvorada do Sul","86150000","86159999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Porecatu","86160000","86164999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Florestópolis","86165000","86169999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Sertanópolis","86170000","86179999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Cambé","86180000","86195999","1","2", "", "", "", "", "", "", "", ""),
		("PR","Ibiporã","86200000","86209999","2","3", "", "", "", "", "", "", "", ""),
		("PR","Jataizinho","86210000","86219999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Assaí","86220000","86224999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Santa Cecília do Pavão","86225000","86229999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Nova América da Colina","86230000","86239999","3","4", "", "", "", "", "", "", "", ""),
		("PR","São Sebastião da Amoreira","86240000","86249999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Nova Santa Bárbara","86250000","86269999","4","5", "", "", "", "", "", "", "", ""),
		("PR","São Jerônimo da Serra","86270000","86274999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Terra Nova (São Jerônimo da Serra)","86275000","86279999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Uraí","86280000","86289999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Rancho Alegre","86290000","86299999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Cornélio Procópio","86300000","86309999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Nova Fátima","86310000","86314999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Santo Antônio do Paraíso","86315000","86319999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Congonhinhas","86320000","86329999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Leópolis","86330000","86339999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Sertaneja","86340000","86349999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Santa Mariana","86350000","86359999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Bandeirantes","86360000","86369999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Santa Amélia","86370000","86374999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Itambaracá","86375000","86379999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Andirá","86380000","86384999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Barra do Jacaré","86385000","86389999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Cambará","86390000","86399999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Jacarezinho","86400000","86409999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Ribeirão Claro","86410000","86419999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Carlópolis","86420000","86429999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Santo Antônio da Platina","86430000","86449999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Quatiguá","86450000","86454999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Joaquim Távora","86455000","86459999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Abatiá","86460000","86464999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Guapirama","86465000","86469999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Jundiaí do Sul","86470000","86479999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Conselheiro Mairinck","86480000","86489999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Ribeirão do Pinhal","86490000","86599999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Rolândia","86600000","86608999","3","4", "", "", "", "", "", "", "", ""),
		("PR","São Martinho (Rolândia)","86609000","86609999","2","3", "", "", "", "", "", "", "", ""),
		("PR","Jaguapitã","86610000","86612999","4","6", "", "", "", "", "", "", "", ""),
		("PR","Pitangueiras","86613000","86614999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Miraselva","86615000","86617999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Prado Ferreira","86618000","86619999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Guaraci","86620000","86629999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Centenário do Sul","86630000","86634999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Lupionópolis","86635000","86639999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Cafeara","86640000","86649999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Santo Inácio","86650000","86659999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Santa Inês","86660000","86669999","4","5", "", "", "", "", "", "", "", ""),
		("PR","Itaguajé","86670000","86679999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Nossa Senhora das Graças","86680000","86689999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Colorado","86690000","86699999","7","8", "", "", "", "", "", "", "", ""),
		("PR","Arapongas","86700000","86714999","1","2", "", "", "", "", "", "", "", ""),
		("PR","Aricanduva (Arapongas)","86719000","86719999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Sabáudia","86720000","86729999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Astorga","86730000","86749999","7","8", "", "", "", "", "", "", "", ""),
		("PR","Iguaraçu","86750000","86754999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Ângulo","86755000","86759999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Munhoz de Melo","86760000","86769999","3","4", "", "", "", "", "", "", "", ""),
		("PR","Santa Fé","86770000","86779999","3","4", "", "", "", "", "", "", "", "");
' );

$installer->endSetup();