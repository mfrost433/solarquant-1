<?php

//echo("in getTrainingProgress1<br>");

require_once "/var/www/html/solarquant/classes/node.inc";
require_once "/var/www/html/solarquant/classes/SolarUtility.php";

//if there isn't an existing link 
if ($link = " "){
	//centralize authentication
	$theUtility = new SolarUtility;
	//$link = mysql_connect ($theUtility->dbHost,$theUtility->dbUser,$theUtility->dbPassword) or die ("Could not connect1");
	$link = new mysqli($theUtility->dbHost,$theUtility->dbUser,$theUtility->dbPassword,$theUtility->dbName);
}

// Start the session
		session_start();
		
		//grab the nodeId from session
		//$theNodeId = $_SESSION["theNodeId"];
		
		$theNodeId = 120;
		
		$theNode = new Node();
		$theNode->id = $theNodeId;
		
		//get patternsets
		 $patternSetIds = array();
		 //$patternSetIds = $theNode->getPatternSetIds("completedSuccessfully");
		 $patternSetIds = $theNode->getPatternSetIds("completedOrUnderway");
		 
		 
		 
		
		//get the trainingFiles associated with these pattern sets
		$theTrainingFileIds = $theNode->getTrainingFiles($patternSetIds);
		
		//echo("count theTrainingFileIds".count($theTrainingFileIds)."<br>");
		
		$i = 0;
		while ($i < count($theTrainingFileIds))
		{
		
			$theTrainingFileId = $theTrainingFileIds[$i];
			$outputArray = array();
			
			/* setup sql*/
			//$sql = "select training_datum_id, training_file_id, batch, epoch, when_logged, sse from training_datum where training_file_id = ".$theTrainingFileId;
			//$sql = "select epoch, sse from training_datum where training_file_id = ".$theTrainingFileId." order by batch, epoch";
			//$sql = "SELECT (batch * epoch) AS totalepoch, sse FROM training_datum where training_file_id = ".$theTrainingFileId." ORDER BY batch, epoch";
			//$sql = "SELECT (training_datum_id) AS totalepoch, sse FROM training_datum where training_file_id = ".$theTrainingFileId." ORDER BY training_datum_id";
			//$sql = "SELECT ((batch + 1) * epoch) AS totalepoch, sse FROM training_datum where training_file_id = ".$theTrainingFileId." ORDER BY training_datum_id";
			
			$sql = "SELECT when_logged, sse FROM training_datum where training_file_id = ".$theTrainingFileId." ORDER BY training_datum_id";
			
			//$sql .= " LIMIT 20000";
	
			//echo("add training file sql:". $sql. "<br>");
			
			//create utility
			$theUtility = new SolarUtility;
	
			//break;
			/* execute sql*/
			$result = mysql_db_query($theUtility->dbName,"$sql") or die ("get training datum sql failed");
			
			//$data = array();
			
			//$outputText = "totalepoch,sse\n";
	    
			//for ($x = 0; $x < mysql_num_rows($result); $x++) {
			//	$data[] = mysql_fetch_assoc($result);
			//}
	    
			//echo json_encode($data); 
			
	
	    
			/* loop through results*/
			//while ($row = mysql_fetch_array ($result))
			while ($row = $result->fetch_assoc())
			{
				//$outputText .= $row["totalepoch"].",".$row["sse"]."\n";	
				$outputArray[$row["when_logged"]] = $row["sse"];
				//$data[$key] = $value;
				
			}
			
			$outputText .= '"TF'.$theTrainingFileId.'":'.json_encode($outputArray);
			
			if ($i < (sizeof($theTrainingFileIds) - 1))
			{
				$outputText .= ',';
			}
			
			$i++;
		}
		
				$finalOutputText .= '{ '.$outputText.'}';
				
			//$finalOutputText = '{ "TF329": {"117":"0.000407198","397":"0.000254493","684":"0.000239332","969":"0.000233023","1249":"0.000232979","1535":"0.000231128","1815":"0.000226524","2102":"0.000223553","2386":"0.000221156","2667":"0.000219724","2953":"0.00021639","3234":"0.000213729","3518":"0.000211207","3804":"0.000208249","4085":"0.000204489","4372":"0.000203904","4652":"0.00020295","4937":"0.000198673","5223":"0.000197925","5504":"0.000195382","5790":"0.000193459","6072":"0.000189365","6355":"0.000188465","6642":"0.000185034","6922":"0.000181556","7209":"0.000178806","7493":"0.000178729","7776":"0.000173537","8060":"0.000167028","8627":"0.000161206","8913":"0.000159332","9193":"0.000152989","9480":"0.000150842","9761":"0.000153821","90":"0.000507942","662":"0.000261792","1222":"0.000241029","1796":"0.000237116","2358":"0.00023147","2922":"0.000229066","3490":"0.000228414","4056":"0.000223835","4622":"0.000222447","5190":"0.000218663","5754":"0.00021939","6322":"0.00021293","6876":"0.000213414","7288":"0.00020921","7692":"0.000208449","8252":"0.000206797","8826":"0.000203195","9388":"0.000200321","9956":"0.000199681","10528":"0.000198961","11092":"0.000195556","11666":"0.000192768","12226":"0.000191758","12800":"0.000186268","13934":"0.000185534","14502":"0.000179096","15072":"0.000177893","15638":"0.000174808","16208":"0.000170768","16772":"0.000169634","17342":"0.000163465","17910":"0.000163843","18476":"0.000152406","19046":"0.000152505","19612":"0.000152857","270":"0.000455136","1122":"0.000260442","1971":"0.000242289","2823":"0.000233421","3648":"0.000232708","4506":"0.000230125","5358":"0.0002284","6204":"0.000223416","7056":"0.000221918","7908":"0.000219227","8757":"0.000215483","9606":"0.000212206","10455":"0.000210343","11307":"0.000208471","12159":"0.000206882","13008":"0.00020718","13857":"0.000207615","14703":"0.00019946","16413":"0.000194045","17253":"0.000197068","18105":"0.000192229","18951":"0.000187498","19803":"0.000189285","20655":"0.000183883","21501":"0.000179727","22353":"0.000177597","23211":"0.000173691","24051":"0.00017147","24900":"0.000167998","25749":"0.000161798","26598":"0.000160558","27459":"0.000158222","28299":"0.000149444","29151":"0.000146592","29970":"0.0001512"},"TF330": {"132":"0.000910803","456":"0.000862781","781":"0.000827132","1109":"0.000821868","1758":"0.000759953","2079":"0.00080754","2408":"0.0007944","2733":"0.000646102","3054":"0.000614001","3382":"0.000638665","3703":"0.000595304","4029":"0.000597036","4357":"0.000583398","4678":"0.000578662","5006":"0.000585513","5326":"0.000638372","5654":"0.000560056","5980":"0.000565819","6261":"0.000571851","6542":"0.000545273","6868":"0.000550617","7189":"0.000571962","7517":"0.000527259","7839":"0.000551936","8164":"0.000540574","8492":"0.000542442","8814":"0.000542492","9142":"0.000565358","9464":"0.000541673","9790":"0.00051824","238":"0.000943871","880":"0.000686795","1536":"0.000689215","2834":"0.000620141","3484":"0.000627994","4132":"0.00060487","4784":"0.000629272","5434":"0.000596048","6078":"0.000607335","6734":"0.000611076","7376":"0.000604515","8034":"0.000584941","8686":"0.000604526","9326":"0.000613469","9982":"0.000606583","10624":"0.000587028","11280":"0.000593816","11934":"0.000593857","12576":"0.000599788","13234":"0.00058016","13876":"0.000609355","14532":"0.000590779","15184":"0.000602862","15826":"0.000595725","16484":"0.000578638","17126":"0.000597826","17778":"0.00058775","18436":"0.000608363","19078":"0.000600763","19734":"0.000606322","579":"0.000864933","1551":"0.00085018","3495":"0.000801439","4470":"0.000815459","5445":"0.000804332","6414":"0.000765187","7389":"0.000704438","8358":"0.000795887","9333":"0.00057659","10308":"0.000581596","11277":"0.000558378","12255":"0.000568122","13221":"0.000566079","14196":"0.000559343","15180":"0.000594513","16140":"0.000523975","17118":"0.00056244","18099":"0.000545933","19062":"0.000543714","20037":"0.000526038","21006":"0.000568901","21981":"0.000553909","22956":"0.00054451","23925":"0.000533605","24900":"0.000522389","25869":"0.000513233","26844":"0.000534744","27828":"0.000506988","28788":"0.000558875","29772":"0.000496369"},"TF319": {"116":"0.00277454","395":"0.00267427","677":"0.0026932","957":"0.0024411","1222":"0.00245572","1502":"0.00225849","1782":"0.00217494","2064":"0.0020804","2344":"0.00209887","2627":"0.00208937","2909":"0.00210196","3189":"0.00197479","3455":"0.00200419","3735":"0.0018816","4016":"0.00184437","4300":"0.00180859","4578":"0.00180477","4862":"0.00180896","5143":"0.00164485","5405":"0.00171364","5689":"0.00172652","5967":"0.00179035","6248":"0.00159136","6533":"0.00158908","7096":"0.00155631","7374":"0.0015813","7642":"0.00162752","7924":"0.0015825","8202":"0.00153313","8487":"0.00171928","8765":"0.00152665","9048":"0.00147534","9332":"0.00149957","9592":"0.0015551","9876":"0.00152812","314":"0.0028765","874":"0.00280371","1438":"0.00272639","1994":"0.0024303","2562":"0.00226735","3126":"0.00227844","3650":"0.00217871","4214":"0.00215001","4770":"0.0021559","5338":"0.00197789","5906":"0.00212539","6462":"0.00189054","7026":"0.00215007","7550":"0.00192362","8116":"0.00178138","8680":"0.00183812","9238":"0.00208302","9800":"0.00180818","10922":"0.00196774","11490":"0.00159206","12012":"0.00167849","12574":"0.00163523","13136":"0.00167883","13696":"0.00160083","14264":"0.0015958","14818":"0.00166551","15382":"0.00172849","15912":"0.00169826","16470":"0.00151748","17032":"0.00148812","17592":"0.0016032","18154":"0.00164169","18718":"0.0015773","19278":"0.00150514","19840":"0.00148827","555":"0.0027569","1395":"0.00277452","2238":"0.00273432","3069":"0.00232961","3921":"0.00231257","4764":"0.00212128","5604":"0.00210759","6402":"0.00232073","7239":"0.00208603","8085":"0.00211318","8934":"0.00200973","9765":"0.00199432","11448":"0.00192361","12297":"0.00188788","13140":"0.00185774","13974":"0.00171622","14817":"0.00165602","15657":"0.00173045","16506":"0.001808","17349":"0.00177087","18180":"0.00177848","19026":"0.00156268","19875":"0.00172186","20715":"0.00168284","21558":"0.00164933","22389":"0.00155098","23232":"0.00157011","24084":"0.0016188","24921":"0.0016615","25764":"0.00153323","26598":"0.00149049","27441":"0.00152232","28293":"0.00150288","29130":"0.0013935","29976":"0.00158757"},"TF304": {"111":"0.00221648","396":"0.00173898","661":"0.00160344","943":"0.00150437","1226":"0.00140481","1505":"0.00143233","1791":"0.00147322","2075":"0.00147584","2357":"0.00142758","2622":"0.00142112","2907":"0.00139555","3187":"0.00134004","3472":"0.0013525","3752":"0.00131686","4321":"0.00134398","4603":"0.00139769","4870":"0.00127406","5150":"0.00130448","5434":"0.00128075","5720":"0.00133498","6003":"0.00129031","6287":"0.00135718","6566":"0.0012986","6833":"0.00129652","7119":"0.00132176","7398":"0.00127373","7684":"0.00128676","7966":"0.00123959","8248":"0.00133363","8534":"0.00126352","8813":"0.00125613","9080":"0.00126046","9366":"0.00124204","9645":"0.00124454","9928":"0.00124941","418":"0.00196847","984":"0.00152826","1556":"0.00158288","2078":"0.00142432","2648":"0.00144389","3206":"0.00146769","3776":"0.00139336","4342":"0.00138022","5472":"0.00133758","6042":"0.00132843","6564":"0.0013791","7136":"0.00138621","7694":"0.0013747","8260":"0.00134275","8828":"0.00130686","9386":"0.00137359","9956":"0.00131295","10478":"0.0013241","11046":"0.00130263","11612":"0.00128034","12062":"0.00132754","12586":"0.00129539","13144":"0.00125111","13710":"0.0012901","14280":"0.0012835","14804":"0.00128584","15370":"0.00127958","15928":"0.0012807","16498":"0.00129975","17064":"0.00124549","17626":"0.0012347","18192":"0.0013094","18724":"0.00129683","19286":"0.00123769","19850":"0.0012342","621":"0.0019664","1470":"0.00164179","3165":"0.00147641","4023":"0.00143543","4812":"0.00143633","5664":"0.00139301","6516":"0.00144256","7362":"0.00141204","8211":"0.00142227","9057":"0.00139684","9909":"0.00132518","10710":"0.00134102","11553":"0.00136812","12405":"0.0013705","13263":"0.00131482","14100":"0.00130379","14955":"0.00136808","15801":"0.00133042","16650":"0.00123933","17451":"0.00127167","18300":"0.00133357","19152":"0.00128716","19998":"0.00132964","20850":"0.00133813","21702":"0.00130074","22548":"0.00124757","23349":"0.0012959","24189":"0.00126403","25047":"0.00122066","25899":"0.00130137","26745":"0.00124921","28446":"0.00125391","29295":"0.00122593"},"TF290": {"110":"0.00277704","401":"0.00253781","986":"0.00242552","1279":"0.00238305","1554":"0.00232855","1846":"0.00231267","2139":"0.00224543","2428":"0.00220515","2724":"0.00215266","3017":"0.00209997","3306":"0.00199382","3583":"0.00186197","3874":"0.00186439","4166":"0.00178175","4461":"0.00178784","4750":"0.00168364","5043":"0.00172279","5332":"0.00160854","5627":"0.00170478","5901":"0.00158661","6192":"0.00158318","6485":"0.00158378","6776":"0.00158376","7068":"0.0015911","7361":"0.00157745","7650":"0.00153179","7927":"0.00152921","8219":"0.00154024","8510":"0.00156976","8802":"0.00151499","9093":"0.00150105","9679":"0.00151949","9971":"0.00152276","492":"0.00263584","1068":"0.00256769","1658":"0.00247355","2242":"0.00243794","2824":"0.00238064","3410":"0.00235637","3994":"0.00229648","4538":"0.0022439","5128":"0.00217748","5706":"0.00205016","6296":"0.00202268","6882":"0.00197176","7460":"0.00179881","8046":"0.00171821","8630":"0.00170327","9180":"0.00172662","9766":"0.00159895","10350":"0.00160935","10936":"0.00165049","11520":"0.00154862","12106":"0.001614","12692":"0.0015227","13236":"0.0015421","13828":"0.00150715","14418":"0.00143567","14996":"0.00152179","15582":"0.00150146","16752":"0.00156125","17342":"0.00147283","17884":"0.00146676","18474":"0.00148523","19052":"0.0014405","19638":"0.00139409","342":"0.00287909","1212":"0.00259575","2088":"0.00248723","2907":"0.00246551","3792":"0.00242938","4674":"0.00243779","5547":"0.00239819","6426":"0.00233342","7299":"0.00230475","8178":"0.00228086","9063":"0.002278","9879":"0.00215101","10758":"0.0020689","11640":"0.00198981","12513":"0.00187195","13392":"0.00181647","14268":"0.00172262","15147":"0.00176251","15975":"0.00164661","16851":"0.00166019","17730":"0.00162612","18603":"0.00163383","19482":"0.0016249","21237":"0.00157981","22116":"0.00151204","22932":"0.00151985","23817":"0.00154909","24696":"0.00152387","25572":"0.00151359","26451":"0.0015508","27330":"0.00146051","28206":"0.00148843","29028":"0.0014541","29901":"0.00147692"}}';
		
		echo $finalOutputText;
	
//echo("out getTrainingProgress1<br>");
		
?>
