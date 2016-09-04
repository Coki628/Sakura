<%@ page contentType="text/html;charset=Shift_JIS" %>

<html>
	<head><title>転送先ページ</title></head>
	<body>
		<h1>転送先ページ</h1>
		<p>
		転送元のページは
		<%
			String source = request.getParameter("from");	//ここでvalueを受け取る
			out.print(source);
		%>
		です。
		</p>
	</body>
</html>