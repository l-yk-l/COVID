import mysql.connector as connection

# получает список названий тестовых баз данных для указанной задачи
def getDBs(problem_id):
	connect = connection('localhost', 'root', '', 'covid_test_db', charset = 'koi8r')
	#connect = MySQLdb.connect('localhost', 'root', '', 'covid_test_db')
	cursor = connect.cursor()

	cursor.execute("SELECT db_name FROM sql_problems WHERE problem_id = " + str(problem_id))
	query_res = cursor.fetchall()
	DBs = []
	for qr in query_res:
		DBs.append(qr[0])

	cursor.close()
	connect.close()
	return DBs

def checkUserQuery(problem_id, user_query, need_sort):
	DBs = getDBs(problem_id)

	###########################################################
	# Тут через problem_id скрипт должен получать right_query #
	right_query = "SELECT * FROM table1 ORDER BY id"		  #
	# Реализую когда определюсь со структурой БД              #
	###########################################################
	
	# Если нужна сортировка, то прклеиваем ее в конце запроса
	# С учетом точки с запятой
	if need_sort:
		if user_query[-1] == ';':
			user_query = user_query[:-1]
		user_query += " ORDER BY id;"
	
	# Проход по всем БД для
	for i in range(len(DBs)):
		connect = connection('localhost', 'sql_solver', 'sql_solve', DBs[i], charset = 'koi8r')
		#connect = MySQLdb.connect('localhost', 'sql_solver', 'sql_solve', DBs[i])
		cursor = connect.cursor()

		cursor.execute(user_query)
		user_res = cursor.fetchall()
		cursor.execute(right_query)
		right_res = cursor.fetchall()

		#print(user_res)
		#print(right_res)
		#print()

		cursor.close()
		connect.close()

		if user_res != right_res:
			return i + 1 # фактический номер фейл-теста - WA(i+1)
	return 0 # Accepted

# С ходу в программу поступает id задачи
problem_id = 1

# И запрос пользователя
user_query = "SELECT * FROM table1;"
# или же
#user_query = "SELECT * FROM table1 ORDER BY id"

# Каким-то образом (наверняка с помощью id задачи)
# мы узнаем, предусмотрена ли сортировка условием задачи
# если да, то need_sort = False, иначе = True
# Т.е. мы не сортируем записи, если их и так нужно отсортировать по условию задачи и наоборот
need_sort = True

val = checkUserQuery(problem_id, user_query, need_sort)

if not val:
	print("AC")
else:
	print("WA" + str(val))