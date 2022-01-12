<?php

if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('eqType', 'vigilancemeteo');
$eqLogics = eqLogic::byType('vigilancemeteo');

?>

<div class="row row-overflow">
  <div class="col-lg-2 col-sm-3 col-sm-4" id="hidCol" style="display: none;">
    <div class="bs-sidebar">
      <ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
        <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
        <?php
        foreach ($eqLogics as $eqLogic) {
          echo '<li class="cursor li_eqLogic" data-eqLogic_id="' . $eqLogic->getId() . '"><a>' . $eqLogic->getHumanName(true) . '</a></li>';
        }
        ?>
      </ul>
    </div>
  </div>

  <div class="col-lg-12 eqLogicThumbnailDisplay" id="listCol">
    <legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
    <div class="eqLogicThumbnailContainer logoPrimary">

      <div class="cursor eqLogicAction logoSecondary" data-action="add">
          <i class="fas fa-plus-circle"></i>
          <br/>
        <span>{{Ajouter}}</span>
      </div>
      <div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
        <i class="fas fa-wrench"></i>
        <br/>
        <span>{{Configuration}}</span>
      </div>

    </div>

    <input class="form-control" placeholder="{{Rechercher}}" id="in_searchEqlogic" />

    <legend><i class="fas fa-home" id="butCol"></i> {{Mes Equipements}}</legend>
    <div class="eqLogicThumbnailContainer">
      <?php
      foreach ($eqLogics as $eqLogic) {
        $opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
        echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff ; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
        echo "<center>";
        echo '<img src="plugins/vigilancemeteo/plugin_info/vigilancemeteo_icon.png" height="105" width="95" />';
        echo "</center>";
        echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
        echo '</div>';
      }
      ?>
    </div>
  </div>


    <div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
        <a class="btn btn-success eqLogicAction pull-right" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
        <a class="btn btn-danger eqLogicAction pull-right" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
        <a class="btn btn-default eqLogicAction pull-right" data-action="configure"><i class="fas fa-cogs"></i> {{Configuration avancée}}</a>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
            <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
            <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Commandes}}</a></li>
        </ul>
        <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
            <div role="tabpanel" class="tab-pane active" id="eqlogictab">
                <br/>
                <form class="form-horizontal">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Nom}}</label>
                            <div class="col-sm-3">
                                <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                                <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement Vigilances Météo}}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" >{{Objet parent}}</label>
                            <div class="col-sm-3">
                                <select class="form-control eqLogicAttr" data-l1key="object_id">
                                    <option value="">{{Aucun}}</option>
                                    <?php
                                    foreach (jeeObject::all() as $object) {
                                        echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Catégorie}}</label>
                            <div class="col-sm-8">
                                <?php
                                foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                                    echo '<label class="checkbox-inline">';
                                    echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                                    echo '</label>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"></label>
                            <div class="col-sm-8">
                                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
                                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label" >{{Type de Vigilance}}</label>
                            <div class="col-sm-3">
                                <select id="typeEq" class="form-control eqLogicAttr" data-l1key="configuration" data-l2key="type">
                                    <option value="vigilance">{{Vigilance Météo France}}</option>
                                    <option value="pluie1h">{{Pluie à 1h Météo France}}</option>
                                    <option value="crue">{{Vigicrues}}</option>
                                    <option value="maree">{{Marées}}</option>
                                    <option value="surf">{{Surf}}</option>
                                    <option value="plage">{{Météo des Plages}}</option>
                                    <option value="air">{{Qualité d'Air}}</option>
                                    <option value="pollen">{{Index Pollens}}</option>
                                    <option value="gdacs">{{Alertes Globales}}</option>
                                </select>
                            </div>
                        </div>

                        <div id="villeEq" class="form-group" style="display:none">
                            <label class="col-sm-3 control-label" >{{Ville}}</label>
                            <div class="col-sm-3">
                                <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="villeNom" type="text" placeholder="{{Ville}}" id="mfVilleNom" disabled>
                            </div>
                            <div class="col-sm-3">
                                <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ville" type="text" placeholder="{{ID Ville}}" id="mfVilleId" disabled>
                            </div>
                            <div class="col-sm-3">
                                <a class="btn btn-default" id='btnSearchCity'><i class="fas fa-search"></i> {{Trouver la ville}}</a>
                            </div>
                        </div>

                        <div id="portEq" class="form-group" style="display:none">
              <div class="form-group">
                            <label class="col-sm-3 control-label">{{Port}}</label>
                            <div class="col-sm-3">
<?php
                    $harbors = vigilancemeteo::mareeListHarbors();
                    if($harbors !== null) {
                      echo "<select type=\"text\" class=\"eqLogicAttr configuration form-control\" data-l1key=\"configuration\" data-l2key=\"port\">";
                      foreach($harbors as $harbor) {
                        echo "<option value=" . $harbor['id'];
                        if(!isset($harbor['latitude']) || !isset($harbor['longitude'])) {
                          echo " disabled";
                        }
                        echo ">" .$harbor['name'] ." " .(isset($harbor['CP'])?$harbor['CP']:'---') ."</option>\n";
                      }
                      echo "</select>";
                    }
                    else {
                      echo "<input type=\"text\" class=\"eqLogicAttr configuration form-control\" data-l1key=\"configuration\" data-l2key=\"port\" placeholder=\"52 pour Saint-Malo voir le site maree.info\"/>";
                    }
?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" ></label>
                <div class="col-sm-3">
                  <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="useTideTemplate" checked/>{{Utiliser la template du plugin}}</label>
                </div>
                            </div>
                        </div>

                        <div id="stationEq" class="form-group" style="display:none">
              <div class="form-group">
                            <label class="col-sm-3 control-label">{{Station}}</label>
                            <div class="col-sm-3">
                                <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="station" placeholder="exemple 122"/>
                            </div>
                        </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" ></label>
                <div class="col-sm-3">
                  <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="useFloodTemplate" checked/>{{Utiliser la template du plugin}}</label>
                </div>
              </div>
            </div>

                        <div id="geolocEq" class="form-group" style="display:none">
                            <label class="col-sm-3 control-label">{{Localisation à utiliser}}</label>
                            <div class="col-sm-3">
                                <select class="form-control eqLogicAttr configuration" id="geoloc" data-l1key="configuration" data-l2key="geoloc">
                                    <?php
                                    $none = 0;
                                    if (class_exists('geotravCmd')) {
                                        foreach (eqLogic::byType('geotrav') as $geoloc) {
                                            if ($geoloc->getConfiguration('type') == 'location') {
                                                $none = 1;
                                                echo '<option value="' . $geoloc->getId() . '">' . $geoloc->getName() . '</option>';
                                            }
                                        }
                                    } 
                                    if ((config::byKey('info::latitude') != '') && (config::byKey('info::longitude') != '') && (config::byKey('info::postalCode') != '') && (config::byKey('info::stateCode') != '')) {
                                        echo '<option value="jeedom">Configuration Jeedom</option>';
                                        $none = 1;
                                    }
                                    if ($none == 0) {
                                        echo '<option value="">Pas de localisation disponible</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div id="pollenEq" class="form-group" style="display:none">
                          <label class="col-sm-3 control-label"></label>
                          <div class="col-sm-8">
                            <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="displayNullPollen" checked/>{{Afficher les pollens niveau 0}}</label>
                          </div>
                        </div>

                        <div id="breezeEq" class="form-group" style="display:none">
                            <label class="col-sm-3 control-label">{{Clef}} <a href='http://aqicn.org/api/'>AQICN</a></label>
                            <div class="col-sm-3">
                                <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="aqicn" placeholder="exemple 122"/>
                            </div>
                        </div>

                        <div id="surfEq" class="form-group" style="display:none">
                            <label class="col-sm-3 control-label">{{ID Spot}}</label>
                            <div class="col-sm-3">
                                <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="surf" placeholder="exemple 1"/>
                            </div>
                        </div>

                        <div id="mswEq" class="form-group" style="display:none">
                            <label class="col-sm-3 control-label">{{Clef}} <a href='http://magicseaweed.com/developer/api'>Magicseaweed</a></label>
                            <div class="col-sm-3">
                                <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="magicseaweed" placeholder="clef API"/>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div role="tabpanel" class="tab-pane" id="commandtab">
                <br/>
                <table id="table_cmd" class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th style="width: 100px;">#</th>
                            <th style="width: 300px;">{{Nom}}</th>
                            <th style="width: 200px;">{{Options}}</th>
                            <th style="width: 150px;"></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

                <form class="form-horizontal">
                    <fieldset>
                        <div class="form-actions">
                            <a class="btn btn-danger eqLogicAction" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
                            <a class="btn btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_file('desktop', 'vigilancemeteo', 'js', 'vigilancemeteo'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>
