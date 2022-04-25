from mysql.connector import connect

# получает список названий тестовых баз данных для указанной задачи
def getDBs(problem_id):
	conn = connect(host='localhost', user='root',
			password='', database='covid_test_db')
	select_dbs = "SELECT db_name FROM sql_problems WHERE problem_id = " + str(problem_id)
	cursor = conn.cursor()
	cursor.execute(select_dbs)
	query_res = cursor.fetchall()
	DBs = []
	for qr in query_res:
		DBs.append(qr[0])
	cursor.close()
	conn.close()
	return DBs

def getQueryRes(query, database):
	conn = connect(host='localhost', user='sql_solver',
				password='sql_solve', database=database)
	cursor = conn.cursor()
	cursor.execute(query)
	return cursor.fetchall()

def checkUserQuery(problem_id, user_query, right_query, need_sort):
	# Получаем список тест-БД
	DBs = getDBs(problem_id)

	# Если нужна сортировка, то прклеиваем ее в конце запроса
	# С учетом точки с запятой
	if need_sort:
		if user_query[-1] == ';':
			user_query = user_query[:-1]
		user_query += " ORDER BY id;"

	# Проход по всем БД для проверки
	for i in range(len(DBs)):
		user_res = getQueryRes(user_query, DBs[i])
		right_res = getQueryRes(right_query, DBs[i])

		print(user_res)
		print(right_res)
		print()

		if user_res != right_res:
			return i + 1 # фактический номер фейл-теста - WA(i+1)
	return 0 # Accepted


print(getDBs(1))


right_query = "SELECT * FROM table1 ORDER BY id"

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

val = checkUserQuery(problem_id, user_query, right_query, need_sort)

if not val:
	print("AC")
else:
	print("WA" + str(val))