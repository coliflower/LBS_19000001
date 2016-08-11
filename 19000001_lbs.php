###[HELP]###

Upload by Coliflower
LBS19000001 "iTunes-SERVER"

V 0.1.0, April 30th, 2016:
First release

V 0.1.1, May 15th, 2016
Some  addaptations ... there is a need to re-structure the logic-site/connection between the LBS.


#Zweck:
Sinnvoll nur auf Apple, da Apple-Skripte ... zur Steuerung von iTunes verwendet werden.
Neben diesem LBS "Server" werden auch die LBS "Player", "Track" und "AirPlay" sowie optional "Playlist"
und "eq" benötigt.

#Benötigte Dateien:
Apache-WebServer am Mac aktivieren und konfigurieren ... Mac-User ist das Zielverzeichnis.
Apple-Skripte - das Installationspaket ist unter folgendem Link zu finden: http://ipfelgruen.de/forum/software/skripte/eigene-tools-und-gadgets/2283-itunes-und-airplay-über-webserver-steuern
Ursprünglich wurden die Skripte für den GIRA-HS4 und EibPC entwickelt/angepasst (Senden/Empfangen) - diser LBS 19000001 kann die Skripte zur Steuerung von iTunes verwenden,
die Implementierung wird im LBS "Player" umgesetzt, als Rückmeldung werden die JSON-Dateien benötigt.
Hier auch ein Danke an Michael für die großartige Unterstützung und Perfektionierung der Aplle-Skripte sowie der einfachen Installationsroutine.
Für die Generierung benötigte JSON-Dateien bitte entweder eine individuelle Lösung entwickeln ODER den Tune?Instructor installieren (www.tune-instructor.de).
An dieser Stelle noch mein Dank an Tibor, den Entwickler von Tune?Instructor der das EXPORT (JSON-Dateien) implementiert hat.
Beschreibung für die Konfiguration von Tune?Instructor liegt in der 19000001_lbs.ZIP-Datei im Downloadbereich.

#Eingänge:
E1: TRIGGER: Systemzeit (KO 5) = 1 Sekunde, andere Trigger je nach Bedarf.
E2: DEBUG: Standard = 4.
E3: IP-ADRESSE: des Zielsystems auf dem der WebServer installiert ist und die benötigte Dateien liegen (am Mac).
E4: PATH: für die Dateiablage am NFS-SHARE in EDOMI anstatt am WebServer.
E5: USER-Name am Mac (benötigt für den WebServer).
E10: MODUS: http OR file.
E11: NAME der JSON-Datei für den String "Player".
E12: NAME der JSON-Datei für den String "Airplay".
E13: NAME der JSON-Datei für den String "Playlists".
E14: NAME der JSON-Datei für den String "eq".
E15: NAME der JSON-Datei für den String "Track".

#Ausgänge:
A1: LINK: bei Modus FILE ein SHARE und bei Modus HTTP ein URL.
A10: MODUS: darf nicht leer sein - entweder "file" oder "http".
A11: JSON-String für den LBS "Player".
A12: JSON-String für den LBS "Airplays".
A13: JSON-String für den LBS "Playlists".
A14: JSON-String für den LBS "eq".
A15: JSON-String für den LBS "Track".

#http/file:
E10: wenn "file" dann Zugriff auf JSON + JPG über NFS-Freigabe in EDOMI (muss vorher erstellt werden).
E10: wenn "http" dann Zugriff auf JSON + JPG über Web-Server am Mac (Apache).

#Logik:
blablablablabla ... siehe Bild im Zip-File.

#Log-Level (Beschreibung bei @wintermute 'geklaut'):
Wird ein Log-Level gesetzt, so werden nur Meldungen mit einer Priorität kleiner des gegebenen Log-Levels ins Logfile eingetragen.
Die Prioritäten für Log-Einträge entsprechen den Un*x-Syslog Leveln und lauten:
0: Emergency
1: Alert
2: Critical
3: Error
4: Warning
5: Notice
6: Informational
7: Debug

###[/HELP]###


###[DEF]###

[name = iTunes-SERVER]

[e#1  = Trigger]
[e#2  = Debug  ]
[e#3  = IP]
[e#4  = Path/Share]
[e#5  = User]
[e#6  = ]
[e#7  = ]
[e#8  = ]
[e#9  = ]
[e#10 = MODUS (http/file)]
[e#11 = .json]
[e#12 = .json]
[e#13 = .json]
[e#14 = .json]
[e#15 = .json]

[a#1  = Url LINK]
[a#2  = ]
[a#3  = ]
[a#4  = ]
[a#5  = ]
[a#6  = ]
[a#7  = ]
[a#8  = ]
[a#9  = ]
[a#10 = MODUS]
[a#11 = player JSON]
[a#12 = airplay JSON]
[a#13 = playlists JSON]
[a#14 = eq JSON]
[a#15 = track JSON]

[v#1  = 0]
[v#2  = 0]

###[/DEF]###



###[LBS]###
<?php

function LB_LBSID($id) {
	
	if ($E=getLogicEingangDataAll($id)) {
    
        $deb = $E[2]['value'];
        $ip4 = $E[3]['value'];
        $nfs = $E[4]['value'];
        $url = 'http://'.$ip4.'/%7E'.$E[5]['value'].'/';
        
        if ($E[1]['refresh'] == 1) {
        
            // EINGÄNGE
        
            if (isEmpty($E[10]['value'])) {
                LB_LBSID_DEBUG($id,'iTunes | E10 | MODUS http OR file at E10 is MISSING -->> E10 is EMPTY',3);
            } elseif (($E[10]['value'] != 'http') AND ($E[10]['value'] != 'file')) {
                LB_LBSID_DEBUG($id,'iTunes | E10 | ONLY http OR file at E10 is possible, NOT -->> '.$E[10]['value'],3);
            } elseif ($E[10]['value'] == 'http' AND isEmpty($E[5]['value'])) {
                LB_LBSID_DEBUG($id,'iTunes | E05 | MODUS http chosen, WebServer-USER at E5 is MISSING... '.$E[5]['value'],3);
            } elseif ($E[10]['value'] == 'file' AND isEmpty($E[4]['value'])) {
                LB_LBSID_DEBUG($id,'iTunes | E04 | MODUS file chosen, NFS-Path at E4 is MISSING... '.$E[4]['value'],3);
            } else if (isEmpty($E[11]['value']) OR isEmpty($E[12]['value']) OR isEmpty($E[13]['value']) OR isEmpty($E[14]['value']) OR isEmpty($E[15]['value'])) {
                LB_LBSID_DEBUG($id,'iTunes | E11 | JSON-file-name on E11 is missing ? : '.$E[11]['value'],3);
                LB_LBSID_DEBUG($id,'iTunes | E12 | JSON-file-name on E12 is missing ? : '.$E[12]['value'],3);
                LB_LBSID_DEBUG($id,'iTunes | E13 | JSON-file-name on E13 is missing ? : '.$E[13]['value'],3);
                LB_LBSID_DEBUG($id,'iTunes | E14 | JSON-file-name on E14 is missing ? : '.$E[14]['value'],3);
                LB_LBSID_DEBUG($id,'iTunes | E15 | JSON-file-name on E14 is missing ? : '.$E[15]['value'],3);
            }
        
            // HTTP
        
            if ($E[10]['value'] == 'http' AND !isEmpty($E[5]['value']) == true) {
                if (isEmpty($E[3]['value']) == true) {
                    LB_LBSID_DEBUG($id,'iTunes | E03 | MODUS is http, IP-Adress is EMPTY -->> Please put a valid IPv4',3);
                } elseif (!isEmpty($E[3]['value'] == true) AND (filter_var($E[3]['value'],FILTER_VALIDATE_IP,FILTER_FLAG_IPV4) != true)) {
                    LB_LBSID_DEBUG($id,'iTunes | E03 | IP is NOT a valid IPv4-Address -->> '.$E[3]['value'],3);
                } elseif (!isEmpty($E[3]['value'] == true) AND (filter_var($E[3]['value'],FILTER_VALIDATE_IP,FILTER_FLAG_IPV4) == true)) {
                    LB_LBSID_DEBUG($id,'iTunes | E03 | IPv$ is -->> '.$ip4,7);
                    
                    if (getLogicElementVar($id,1)!=1) {
                        setLogicElementVar($id,1,1);
                        callLogicFunctionExec(LBSID,$id);
                    }
                }
            }

            // FILE
        
            if ($E[10]['value'] == 'file' AND is_dir($nfs) != true) {
                LB_LBSID_DEBUG($id,'iTunes | E04 | Modus is FILE but no valid PATH chosen at E04: '.$nfs,3);
            } else if ($E[10]['value'] == 'file' AND is_dir($nfs) == true) {
                LB_LBSID_DEBUG($id,'iTunes | E04 | Modus is FILE (NFS-Share), PATH  at E04 is: '.$nfs,7);
                if (getLogicElementVar($id,2)!=1) {
                    setLogicElementVar($id,2,1);
                    callLogicFunctionExec(LBSID,$id);
                }
            }
        }
    }
}

function LB_LBSID_DEBUG($id,$s,$l=6) {
    $a=array("Emergency","Alert","Critical","Error","Warning","Notice","Informational","Debug");
    $E=getLogicEingangDataAll($id);
    $deb=$E[2]['value'];
    $l<$deb && writeToCustomLog("LBSLBSID",$l,"(ID$id) ".$a[$l].": ".$s); // writeToCustomLog($LogName,$LogLevel,$LogMessage)
}

?>
###[/LBS]###


###[EXEC]###
<?php

require(dirname(__FILE__)."/../../../../main/include/php/incl_lbsexec.php");
set_time_limit(1);
sql_connect();

if ($E=getLogicEingangDataAll($id)) {
    
    $www = 'http://'.$E[3]['value'].'/';
    $ip4 = $E[3]['value'];
	$nfs = $E[4]['value'];
    $url = 'http://'.$ip4.'/%7E'.$E[5]['value'].'/';


    // HTTP
            
    if (getLogicElementVar($id,1) == 1) {
        try {
            $ch = curl_init($www);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch,CURLOPT_TIMEOUT,1);
            $re = curl_exec($ch);
            if (!$re) throw new Exception(curl_error($ch));
            curl_close($ch);
        }
        catch (Exception $e) {
            LB_LBSID_TRACE($id,'Exception: '.$e->getMessage(),3);
            exit();
        }
            
        if (!isEmpty($E[11]['value']) == true) {
            $urlplayer = $url.$E[11]['value'].'.json';
            $headers_1 = get_headers($urlplayer);
			
            if ($headers_1[0] == 'HTTP/1.1 404 Not Found') {
                $player__ = '{"state":""}'; // dummy
                LB_LBSID_TRACE($id,'iTunes | E11 | 404-ERROR-Code -->> JSON-file on web-server not found',7);
            } else {
            	$player__ = file_get_contents($urlplayer);
                LB_LBSID_TRACE($id,'iTunes | E11 | 404-ERROR-Code does not exists (player-JSON available) -->> '.$player__,7);
            }
        }
        
        if (!isEmpty($E[12]['value']) == true) {
            $urlairplays = $url.$E[12]['value'].'.json';
            $headers_2 = get_headers($urlairplays);
			
            if ($headers_2[0] == 'HTTP/1.1 404 Not Found') {
                $airplays = '{"devices":[{"network address":"","selected":""}]}'; // dummy
			    LB_LBSID_TRACE($id,'iTunes | E12 | 404-ERROR-Code -->> JSON-file on web-server not found',7);
            } else {
                $airplays = file_get_contents($urlairplays);
                LB_LBSID_TRACE($id,'iTunes | E12 | 404-ERROR-Code does not exists (airplay-JSON available) -->> '.$airplays,7);
            }
        }

        if (!isEmpty($E[13]['value']) == true) {
            $urlplaylist = $url.$E[13]['value'].'.json';
            $headers_3 = get_headers($urlplaylist);
			
            if ($headers_3[0] == 'HTTP/1.1 404 Not Found') {
                $playlist = '{"playlists":[{"time":"","shuffle":"","name":""}]}'; // dummy
			    LB_LBSID_TRACE($id,'iTunes | E13 | 404-ERROR-Code -->> JSON-file on web-server not found',7);
            } else {
                $playlist = file_get_contents($urlplaylist);
		    	LB_LBSID_TRACE($id,'iTunes | E13 | 404-ERROR-Code does not exists (playlist-JSON available) -->> '.$airplays,7);
            }
        }
        
        if (!isEmpty($E[14]['value']) == true) {
            $urlequalizer = $url.$E[14]['value'].'.json';
            $headers_4 = get_headers($urlequalizer);
			
            if ($headers_4[0] == 'HTTP/1.1 404 Not Found') {
                $equalizer = '{"presets":[{"name":"","preamp":""}]}'; // dummy
			    LB_LBSID_TRACE($id,'iTunes | E14 | 404-ERROR-Code -->> JSON-file on web-server not found',7);
            } else {
                $equalizer = file_get_contents($urlequalizer);
		    	LB_LBSID_TRACE($id,'iTunes | E14 | 404-ERROR-Code does not exists (playlist-JSON available) -->> '.$equalizer,7);
            }
        }
        
        if (!isEmpty($E[15]['value']) == true) {
            $urltracks = $url.$E[15]['value'].'.json';
            $headers_5 = get_headers($urltracks);
			
            if ($headers_5[0] == 'HTTP/1.1 404 Not Found') {
                $tracks__ = '{"name":""}'; // dummy
			    LB_LBSID_TRACE($id,'iTunes | E15 | 404-ERROR-Code -->> JSON-file on web-server not found',7);
            } else {
                $tracks__ = file_get_contents($urltracks);
                LB_LBSID_TRACE($id,'iTunes | E15 | 404-ERROR-Code does not exists (tracks-JSON available) -->> '.$tracks__,7);
            }
        }
        setLogicElementVar($id,1,0);
	}
        
    // FILE
    
    if (getLogicElementVar($id,2) == 1) {
    
		if (!file_exists($nfs.$E[11]['value'].'.json') == true) {
			$player__ = '{"state":""}'; // dummy;
			LB_LBSID_TRACE($id,'iTunes | E11 | 404-ERROR-Code -->> JSON-file on web-server not found',7);
		} else {
			$player__ = file_get_contents($nfs.$E[11]['value'].'.json');
            LB_LBSID_TRACE($id,'iTunes | E11 | player.JSON exists: '.$player__,7);
		}
            
		if (!file_exists($nfs.$E[12]['value'].'.json') == true) {
            $airplays = '{"devices":[{"network address":"","selected":""}]}'; // dummy
			LB_LBSID_TRACE($id,'iTunes | E12 | 404-ERROR-Code -->> JSON-file on web-server not found',7);
		} else {
			$airplays = file_get_contents($nfs.$E[12]['value'].'.json');
		    LB_LBSID_TRACE($id,'iTunes | E12 | airplays.JSON exists: '.$airplays,7);
		}
            
		if (!file_exists($nfs.$E[13]['value'].'.json') == true) {
            $playlist = '{"playlists":[{"time":"","shuffle":"","name":""}]}'; // dummy
			LB_LBSID_TRACE($id,'iTunes | E13 | 404-ERROR-Code -->> JSON-file on web-server not found',3);
		} else {
			$playlist = file_get_contents($nfs.$E[13]['value'].'.json');
		    LB_LBSID_TRACE($id,'iTunes | E13 | playlist.JSON exists: '.$playlist,7);
		}
		
		if (!file_exists($nfs.$E[14]['value'].'.json') == true) {
            $equalizer = '{"presets":[{"name":"","preamp":""}]}'; // dummy
			LB_LBSID_TRACE($id,'iTunes | E14 | 404-ERROR-Code -->> JSON-file on web-server not found',3);
		} else {
			$equalizer = file_get_contents($nfs.$E[14]['value'].'.json');
		    LB_LBSID_TRACE($id,'iTunes | E14 | eq.JSON exists: '.$equalizer,7);
		}
		
		if (!file_exists($nfs.$E[15]['value'].'.json') == true) {
            $tracks__ = '{"name":""}'; // dummy
			LB_LBSID_TRACE($id,'iTunes | E15 | 404-ERROR-Code -->> JSON-file on web-server not found',7);
		} else {
			$tracks__ = file_get_contents($nfs.$E[15]['value'].'.json');
		    LB_LBSID_TRACE($id,'iTunes | E15 | tracks.JSON exists: '.$tracks__,7);
		}
		
        setLogicElementVar($id,2,0);
	}
	
    setLogicLinkAusgang($id,10,$E[10]['value']);

	if (isset($E[10]['value'])) {
    	if ($E[10]['value'] == 'http') {
       		setLogicLinkAusgang($id,1,$url);
    	} else if ($E[10]['value'] == 'file') {
        	setLogicLinkAusgang($id,1,$nfs);
        }
    }    
    if (isset($player__)) {
		setLogicLinkAusgang($id,11,$player__);
	}
	if (isset($airplays)) {
		setLogicLinkAusgang($id,12,$airplays);
	}
	if (isset($playlist)) {
		setLogicLinkAusgang($id,13,$playlist);
	}
	if (isset($equalizer)) {
		setLogicLinkAusgang($id,14,$equalizer);
	}
	if (isset($tracks__)) {
		setLogicLinkAusgang($id,15,$tracks__);
	}
}

setLogicElementStatus($id,0);

sql_disconnect();

function LB_LBSID_TRACE($id,$s,$l=6) {
    $a=array("Emergency","Alert","Critical","Error","Warning","Notice","Informational","Debug");
    $E=getLogicEingangDataAll($id);
    $deb=$E[2]['value'];
    $l<$deb && writeToCustomLog("LBSLBSID",$l,"(ID$id) ".$a[$l].": ".$s); // writeToCustomLog($LogName,$LogLevel,$LogMessage)
}

?>
###[/EXEC]###
