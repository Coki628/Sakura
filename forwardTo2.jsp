<%@ page contentType="text/html;charset=Shift_JIS" %>

<html>
	<head><title>�]����y�[�W</title></head>
	<body>
		<h1>�]����y�[�W</h1>
		<p>
		�]�����̃y�[�W��
		<%
			String source = request.getParameter("from");	//������value���󂯎��
			out.print(source);
		%>
		�ł��B
		</p>
	</body>
</html>