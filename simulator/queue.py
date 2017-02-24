import sqlalchemy
import os, sys, time, shutil
import pymysql.cursors

engine = sqlalchemy.create_engine('mysql+pymysql://root:pwd@db/code_character')

def queueSubmissions(submissions, currentSubmissions, submissionCount):
    # Queue the new job/jobs
    for i in range(currentSubmissions,submissionCount):
        print('Adding job {0} to the queue'.format(submissions[i]['id']))
        insertNewJobQuery = """
        INSERT INTO `queue` (`submissions_id`,`attempts`) VALUES({0},{1})
        """
        engine.execute(insertNewJobQuery.format(submissions[i]['id'],0))

def checkForNewSubmissionsAndQueueThem():
    startFlag = True
    currentSubmissions = 0
    while True:
        submissions = engine.execute('SELECT * FROM submissions');
        submissionCount = 0
        submissionsList = []
        for row in submissions:
            submissionsList.append(dict(zip(row.keys(), row)))
            submissionCount+=1
        if startFlag:
            currentSubmissions = submissionCount
            startFlag = False
        if currentSubmissions != submissionCount:
            queueSubmissions(submissionsList, currentSubmissions, submissionCount)
        print(submissionCount)
        currentSubmissions = submissionCount
        time.sleep(5)

if __name__=='__main__':
    checkForNewSubmissionsAndQueueThem()
