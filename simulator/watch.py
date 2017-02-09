import sqlalchemy
import os, sys, time, shutil

engine = sqlalchemy.create_engine('mysql+pymysql://root:pwd@localhost:3306/code_character')

startFlag = True
currentSubmissions = 0

def runSubmissions(submissions, currentSubmissions, submissionCount):
    for i in range(currentSubmissions,submissionCount):
        # shutil.copy(submissions[i]['sourceCodePath'],os.getcwd())
        with open('code_{0}.zip'.format(submissions[i]['id']),'wb') as f:
            f.write(submissions[i]['submittedCode'])
            # Write simulator logic here

while True:
    submissions = engine.execute('SELECT * FROM submissions');
    submissionCount = 0
    submissionsList= []
    for row in submissions:
        submissionsList.append(dict(zip(row.keys(), row)))
        submissionCount+=1
    if startFlag:
        currentSubmissions = submissionCount
        startFlag = False
    if currentSubmissions != submissionCount:
        runSubmissions(submissionsList, currentSubmissions, submissionCount)
    print(submissionCount)
    currentSubmissions = submissionCount
    time.sleep(5)
