
<html xmlns="http://www.w3.org/1999/xhtml" style="margin: 0;padding: 0;">
<head>
	<title>Demandas Unicasa</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body style="margin: 0; padding: 0;">
	<table style="min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff">
		<tbody>
			<tr>
				<td valign="top">
					<span><font color="#888888"></font></span>
					<table style="min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0">
						<tbody>
							<tr>
								<td>
									<table style="min-width:100%" width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff">
										<tbody>
											<tr>
												<td>&nbsp;</td>
											</tr>
											<tr>
												<td style="padding-top:10px;padding-bottom:20px" align="center" valign="middle">
													<img src="{{ asset('/assets/images/unicasa.png') }}" width="auto" height="auto">
												</td>
											</tr>
											<tr>
												<td height="40" align="center" valign="top">
													<table width="602" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff" style="border-left: 0px solid #232020; border-top: 1px solid #232020; border-right: 0px solid #232020; border-bottom: 0px solid #fff;">
														<tbody>
															<tr>
																<td height="40">&nbsp;</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff">
										<tbody>
											<tr>
												<td style="text-align:center" valign="top">
													<table style="margin:0 auto" width="600" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff">
														<tbody>
															<tr>
																<td style="text-align:left" valign="top">
																	<table width="100%" cellspacing="0" cellpadding="0" border="0">
																		<tbody>
																			<tr>
																				<td width="30">&nbsp;</td>
																				<td width="540">
																					<table width="100%" cellspacing="0" cellpadding="0" border="0">
																						<tbody>
																							<tr>
																								<td valign="top">
																									<h3 style="margin:0px 0px 30px 0px;padding:0px;color:#252525;font-style:normal;font-size:25px;font-family:Calibri,Helvetica,Arial,sans-serif;font-weight:400;line-height:40px;text-decoration:none;text-transform:none;display:block;text-align:center;">Redefinir senha</h3>
​
																									<p style="margin:0px 0px 15px 0px;padding:0px;color:#252525;font-style:normal;font-size:20px;font-family:Calibri,Helvetica,Arial,sans-serif;font-weight:400;line-height:25px;text-decoration:none;text-transform:none;display:block;text-align:center;">Olá, {!! $nome !!}!<br>Clique no link abaixo para redefinir sua senha. Se você não for redirecionado automaticamente, copie o link e cole-o na barra de endereços de seu navegador.</p>
​
																									<a href="{{ $action_link }}" style="margin:0px 0px 20px 0px;padding:0px;color:#252525;font-style:normal;font-size:18px;font-family:Calibri,Helvetica,Arial,sans-serif;font-weight:700;line-height:27px;text-decoration:underline;text-transform:none;display:block;text-align:center;" target="_blank">{{ $action_link }}</a>
																								</td>
																							</tr>
																						</tbody>
																					</table>
																				</td>
																				<td width="30">&nbsp;</td>
																			</tr>
																		</tbody>
																	</table>
																</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td align="center" valign="top">
									<table width="602" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff">
										<tbody>
											<tr>
												<td align="center" valign="top">
													<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#ffffff" style="border-left: 0px solid #232020; border-bottom: 1px solid #232020; border-right: 0px solid #232020; border-top: 0px solid #fff;">
														<tbody>
															<tr>
																<td height="40">&nbsp;</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
											{{-- <tr>
												<td style="padding-top:20px;padding-bottom:20px" align="center" valign="middle">
													<img src="{{ asset('/assets/media/logos/logo.png') }}" width="auto" height="auto">
												</td>
											</tr> --}}
											<tr>
												<td style="padding-bottom:40px" align="center" width="540" valign="middle">
													<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
														<tbody>
															<tr>
																<td align="center" width="30">&nbsp;</td>
																<td align="left" width="540" valign="top">
																	<p style="margin:0px;padding:0px;color:#002939;font-style:normal;font-size:11px;font-family:Calibri,Helvetica,Arial,sans-serif;font-weight:400;line-height:17px;text-decoration:none;text-transform:none;display:block;text-align:-webkit-center;text-align:center;">Você está recebendo este conteúdo em seu e-mail pois seu e-mail está cadastrado no site
																		<a href="{{ url()->to('/') }}" style="font-weight:bold;margin:0px;border-top-width:0px;border-right-width:0px;border-left-width:0px;border-bottom-width:0px;border-style:none;border-bottom-style:none;padding:0px;float:none;color:#000000;font-style:normal;font-family:Calibri,Helvetica,Arial,sans-serif;font-size:11px;font-weight:bold;line-height:17px;text-transform:none;display:inline;white-space:normal;text-decoration:underline;">{{ url()->to('/') }}</a>.</p>
																	</td>
																	<td align="center" width="30">&nbsp;</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</body>
	</html>