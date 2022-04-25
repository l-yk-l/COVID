from mysql.connector import connect
import sys

# получает список названий тестовых баз данных для указанной задачи
def getDBs(problem_id):
	conn = connect(host='localhost', user='root',
			password='', database='covid')
	select_dbs = "SELECT title FROM sql_testing_db INNER JOIN sql_problem_db ON sql_testing_db.id=sql_problem_db.db_id WHERE sql_problem_db.prob_id = " + str(problem_id)
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

		if user_res != right_res:
			return i + 1 # фактический номер фейл-теста - WA(i+1)
	return 0 # Accepted


user_query = sys.argv[1]
problem_id = int(sys.argv[2])
user_id = int(sys.argv[3])
right_query = sys.argv[4]

# Каким-то образом (наверняка с помощью id задачи)
# мы узнаем, предусмотрена ли сортировка условием задачи
# если да, то need_sort = False, иначе = True
# Т.е. мы не сортируем записи, если их и так нужно отсортировать по условию задачи и наоборот
need_sort = False


try:
	val = checkUserQuery(problem_id, user_query, right_query, need_sort)
except Exception as e:
	print(str(e))

print(val)