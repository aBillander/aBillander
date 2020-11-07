<!DOCTYPE html>
<html lang="{{ \App\Context::getContext()->language->iso_code }}">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style type="text/css" nonce="">
      body,td,div,p,a,input {font-family: arial, sans-serif;}

      body, td {font-size:13px} a:link, a:active {color:#1155CC; text-decoration:none} 
      a:hover {text-decoration:underline; cursor: pointer} 
      a:visited{color:##6611CC} 
      img{border:0px} 
      pre { white-space: pre; white-space: -moz-pre-wrap; white-space: -o-pre-wrap; white-space: pre-wrap; word-wrap: break-word; max-width: 800px; overflow: auto;} 
      .logo { left: -7px; position: relative; }


    </style>
  </head>
  <body>
    <div class="bodycontainer">
      
      
      <div class="maincontent">
        
        
        <table class="message" width="100%" cellspacing="0" cellpadding="0" border="0">
          <tbody>
            
            
            <tr>
              <td colspan="2">
                <table width="100%" cellspacing="0" cellpadding="12" border="0">
                  <tbody>
                    <tr>
                      <td>
                        <div style="overflow: hidden;">
                          <font size="-1">
                            <div link="#0563C1" vlink="#954F72" lang="ES">
                              <div class="m_-2377189133069245543WordSection1">
                                
                                
                                
                                
                                <p class="MsoNormal">Estimados Srs.,<u></u><u></u></p>
                                <p class="MsoNormal"><u></u>&nbsp;<u></u></p>
                                <p class="MsoNormal">Adjuntamos información para presentación del 347. Agradeceriamos nos confirmaran que es correcta con vuestros datos. <u></u><u></u></p>
                                <p class="MsoNormal">Tambien enviamos adjunto el detalle del mayor en el Excel.<u></u><u></u></p>
                                <p class="MsoNormal"><u></u>&nbsp;<u></u></p>
                                <table style="width:621.0pt;border-collapse:collapse" width="828" cellspacing="0" cellpadding="0" border="0">
                                  <tbody>
                                    <tr style="height:15.0pt">
                                      <td style="width:77.0pt;background:#5b9bd5;padding:0cm 3.5pt 0cm 3.5pt;height:15.0pt" width="103" valign="bottom" nowrap="nowrap">
                                        <p class="MsoNormal"><b><span style="color:white">N.I.F.<u></u><u></u></span></b></p>
                                      </td>
                                      <td style="width:206.0pt;background:#5b9bd5;padding:0cm 3.5pt 0cm 3.5pt;height:15.0pt" width="275" valign="bottom" nowrap="nowrap">
                                        <p class="MsoNormal"><b><span style="color:white">NOMBRE<u></u><u></u></span></b></p>
                                      </td>
                                      <td style="width:68.0pt;background:#5b9bd5;padding:0cm 3.5pt 0cm 3.5pt;height:15.0pt" width="91" valign="bottom" nowrap="nowrap">
                                        <p class="MsoNormal"><b><span style="color:white">1T<u></u><u></u></span></b></p>
                                      </td>
                                      <td style="width:68.0pt;background:#5b9bd5;padding:0cm 3.5pt 0cm 3.5pt;height:15.0pt" width="91" valign="bottom" nowrap="nowrap">
                                        <p class="MsoNormal"><b><span style="color:white">2T<u></u><u></u></span></b></p>
                                      </td>
                                      <td style="width:68.0pt;background:#5b9bd5;padding:0cm 3.5pt 0cm 3.5pt;height:15.0pt" width="91" valign="bottom" nowrap="nowrap">
                                        <p class="MsoNormal"><b><span style="color:white">3T<u></u><u></u></span></b></p>
                                      </td>
                                      <td style="width:68.0pt;background:#5b9bd5;padding:0cm 3.5pt 0cm 3.5pt;height:15.0pt" width="91" valign="bottom" nowrap="nowrap">
                                        <p class="MsoNormal"><b><span style="color:white">4T<u></u><u></u></span></b></p>
                                      </td>
                                      <td style="width:66.0pt;background:#5b9bd5;padding:0cm 3.5pt 0cm 3.5pt;height:15.0pt" width="88" valign="bottom" nowrap="nowrap">
                                        <p class="MsoNormal"><b><span style="color:white">Total general (€)<u></u><u></u></span></b></p>
                                      </td>
                                    </tr>
                                    <tr style="height:15.0pt">
                                      <td style="width:77.0pt;padding:0cm 3.5pt 0cm 3.5pt;height:15.0pt" width="103" valign="bottom" nowrap="nowrap">
                                        <p class="MsoNormal"><b><span style="color:black">{{ $customer->identification }}<u></u><u></u></span></b></p>
                                      </td>
                                      <td style="width:206.0pt;padding:0cm 3.5pt 0cm 3.5pt;height:15.0pt" width="275" valign="bottom" nowrap="nowrap">
                                        <p class="MsoNormal"><span style="color:black">{{ $customer->name_fiscal }}<u></u><u></u></span></p>
                                      </td>
                                      <td style="width:68.0pt;padding:0cm 3.5pt 0cm 3.5pt;height:15.0pt" width="91" valign="bottom" nowrap="nowrap">
                                        <p class="MsoNormal" style="text-align:right" align="right"><span style="color:black">{{ \App\Currency::viewMoney( $terms[1] ) }}<u></u><u></u></span></p>
                                      </td>
                                      <td style="width:68.0pt;padding:0cm 3.5pt 0cm 3.5pt;height:15.0pt" width="91" valign="bottom" nowrap="nowrap">
                                        <p class="MsoNormal" style="text-align:right" align="right"><span style="color:black">{{ \App\Currency::viewMoney( $terms[2] ) }}<u></u><u></u></span></p>
                                      </td>
                                      <td style="width:68.0pt;padding:0cm 3.5pt 0cm 3.5pt;height:15.0pt" width="91" valign="bottom" nowrap="nowrap">
                                        <p class="MsoNormal" style="text-align:right" align="right"><span style="color:black">{{ \App\Currency::viewMoney( $terms[3] ) }}<u></u><u></u></span></p>
                                      </td>
                                      <td style="width:68.0pt;padding:0cm 3.5pt 0cm 3.5pt;height:15.0pt" width="91" valign="bottom" nowrap="nowrap">
                                        <p class="MsoNormal" style="text-align:right" align="right"><span style="color:black">{{ \App\Currency::viewMoney( $terms[4] ) }}<u></u><u></u></span></p>
                                      </td>
                                      <td style="width:66.0pt;padding:0cm 3.5pt 0cm 3.5pt;height:15.0pt" width="88" valign="bottom" nowrap="nowrap">
                                        <p class="MsoNormal" style="text-align:right" align="right"><span style="color:black">{{ \App\Currency::viewMoney( $total ) }}<u></u><u></u></span></p>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                                <p class="MsoNormal"><u></u>&nbsp;<u></u></p>
                                <p class="MsoNormal">Gracias y saludos,<u></u><u></u></p>
                                <p class="MsoNormal"><u></u>&nbsp;<u></u></p>
                                <p class="MsoNormal"><u></u>&nbsp;<u></u></p>
                                <p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Comic Sans MS&quot;;color:#1f497d">Lidia Martínez <u></u><u></u></span></p>
                                <p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Comic Sans MS&quot;;color:#1f497d"><a href="https://www.google.com/maps/search/C%2F+Rodriguez+de+la+Fuente,+18+41310+Brenes,+Sevilla,+Spain?entry=gmail&amp;source=g">C/ Rodriguez de la Fuente, 18</a><u></u><u></u></span></p>
                                <p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Comic Sans MS&quot;;color:#1f497d"><a href="https://www.google.com/maps/search/C%2F+Rodriguez+de+la+Fuente,+18+41310+Brenes,+Sevilla,+Spain?entry=gmail&amp;source=g">41310 Brenes, Sevilla, Spain</a><u></u><u></u></span></p>
                                <p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Comic Sans MS&quot;;color:#1f497d">+ 34 692 813 253<u></u><u></u></span></p>
                                <p class="MsoNormal"><span style="font-size:9.0pt;font-family:&quot;Comic Sans MS&quot;;color:#1f497d"><a href="http://www.laextranatural.com/" target="_blank"><span style="color:blue">www.laextranatural.com</span></a> <u></u><u></u></span></p>
                                <p class="MsoNormal"><span style="color:#1f497d"><u></u>&nbsp;<u></u></span></p>
                                <p class="MsoNormal"><span style="color:#1f497d"><img id="m_-2377189133069245543Imagen_x0020_1" src="{{ asset('assets/theme/images/email_company_logo.png') }}" alt="" width="306" height="79" border="0"></span><span style="color:#1f497d"><u></u><u></u></span></p>
                                <p class="MsoNormal"><span style="color:#1f497d"><u></u>&nbsp;<u></u></span></p>
                                <p class="MsoNormal" style="text-align:justify"><span style="font-size:8.0pt;font-family:&quot;Arial&quot;,sans-serif;color:#1f497d">De
                                  acuerdo con los establecido en la Ley de Protección de Datos de 
                                  Carácter Personal, les informamos que los datos que figuran en esta 
                                  comunicación están incluidos en un fichero automatizado propiedad de LA 
                                  EXTRANATURAL, S.L.. Para el ejercicio de los derechos de acceso, 
                                  rectificación, cancelación u oposición, podrán dirigirse en cualquier 
                                  momento a dicha entidad, en el domicilio sito en <a href="https://www.google.com/maps/search/Calle+Dr+F%C3%A9lix+Rodr%C3%ADguez+de+la+Fuente,+18?entry=gmail&amp;source=g">Calle Dr Félix Rodríguez de la Fuente, 18</a>,
                                  41.310 Brenes, Sevilla. Este documento se dirige exclusivamente a su 
                                  destinatario. Por poder contener información confidencial sometida a 
                                  secreto profesional o cuya divulgación esté prohibida en virtud de la 
                                  persona autorizada por éste, que la información contenida en el mismo es
                                  reservada y su utilización o divulgación con cualquier fin está 
                                  prohibida. Si ha recibido este documento por error, le rogamos nos lo 
                                  comunique a <a href="mailto:laextranatural@laextranatural.es" target="_blank">laextranatural@laextranatural.<wbr>es</a> y proceda a su destrucción.<u></u><u></u></span>
                                </p>
                                
                                
                                
                                
                                
                                
                              </div>
                            </div>
                          </font>
                        </div>
                        <br clear="all">
                        
                        
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  

  </body>
</html>