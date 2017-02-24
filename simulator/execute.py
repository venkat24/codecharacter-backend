import sqlalchemy
import os, sys, time, shutil, string, traceback
import pymysql.cursors
import hashlib
import random
import builder

engine = sqlalchemy.create_engine('mysql+pymysql://root:pwd@db/code_character')
max_level = 3

def addNotifications(title, message, teamId):
    getTeamNameQuery="""
    SELECT `teamName` from `teams` WHERE `id`={0}
    """
    team = engine.execute(getTeamNameQuery.format(teamId))
    for row in team:
        teamName = row[0]

    getTeamMembersQuery="""
    SELECT `id` FROM `registrations` WHERE `teamName` = '{0}'
    """
    membersQuery = engine.execute(getTeamMembersQuery.format(teamName))
    teamMembers = []
    for row in membersQuery:
        teamMembers.append(row[0])

    for member in teamMembers:
        insertNotitificationQuery="""
        INSERT INTO `notifications` (`userId`,`title`,`message`,`messageType`,`teamName`)
        VALUES({0},'{1}','{2}','{3}','{4}')
        """
        engine.execute(insertNotitificationQuery.format(member,title,message,"SUBMISSION",teamName))

# WE STIL AREN'T CHECKING IF THE SAME GUY IS SUBMITTING AGAIN
# IF HE DOES, CLEAR THE QUEUE
def runSubmissions():
    while True:
        # Get the whole Queue
        queue = engine.execute('SELECT * FROM `queue`')
        queueList = []
        for row in queue:
            queueList.append(dict(zip(row.keys(), row)))
        # If its empty, do nothing
        if(queueList == []):
            print('No New Jobs')
        for i in range(len(queueList)):
            # Stuff for the notifications
            title = ""
            message = ""
            queueId = queueList[i]['id']
            getSubmissionQuery="""
            SELECT `submittedCode` from `submissions` WHERE `id` = {0}
            """
            submission = engine.execute(getSubmissionQuery.format(queueList[i]['submissions_id']))
            # Submission will only have one row anyway
            for row in submission:
                try:
                    print('Found a new submission!')
                    fileName = 'code_{0}.zip'.format(queueList[i]['id'])
                    f = open(fileName,'wb')
                    f.write(row[0]) # Write the .zip file
                    f.close()
                    print('Fetched code zip file ...')

                    # Set the submission status as running
                    print('Setting submission status ... ')
                    setRunningSubmissionStatusQuery="""
                    UPDATE `submissions` SET `status` = 'RUNNING' WHERE `id` = {0}
                    """
                    engine.execute(setRunningSubmissionStatusQuery.format(queueList[i]['submissions_id']))

                    print('Generating Random String ... ')
                    randomString = ''.join(random.choice(string.ascii_letters + string.digits) for _ in range(50))
                    print(randomString)

                    getSubmissionLevelQuery="""
                    SELECT `levelNo` from `submissions` WHERE `id` = {0}
                    """
                    querySubmissionLevel = engine.execute(getSubmissionLevelQuery.format(queueList[i]['submissions_id']))
                    for row in querySubmissionLevel:
                        currentSubmissionLevel = row[0]

                    try:
                        score = builder.run_simulator(randomString, fileName, currentSubmissionLevel)
                        print(score)

                    except Exception as e:
                        print(e)
                        score=(0,0)

                    print('Simulation complete')
                    # Once the simulation is over, kill the row in the queue
                    deleteFromQueueQuery = """
                    DELETE FROM `queue` WHERE `id` = {0}
                    """
                    engine.execute(deleteFromQueueQuery.format(queueId))

                    # Update the Submission status
                    updateSubmissionStatusQuery="""
                    UPDATE `submissions` SET `status` = '{0}' WHERE `id` = {1}
                    """
                    status=""
                    if score:
                        status="COMPLETED"
                    else:
                        status="FAILED"
                        title="Submission Failed"
                        message="Execution failed due to unknown circumstances. Please check your uploaded code, and verify that it works with the simulator provided."
                    engine.execute(updateSubmissionStatusQuery.format(status, queueList[i]['submissions_id']))

                    # Get the teamId
                    teamId = 0
                    fetchTeamIdQuery="""
                    SELECT `teamId` FROM `submissions` WHERE `id` = {0}
                    """
                    corresSubmission = engine.execute(fetchTeamIdQuery.format(queueList[i]['submissions_id']))
                    for row in corresSubmission:
                        teamId = row[0]

                    # Calculate the new scores
                    currentLevel = 0
                    scoreDifference = score[0] - score[1]
                    isNewHighScore = False
                    isWin = False
                    isHigherLevel = False
                    # Check if the player won the game
                    if scoreDifference > 0:
                        print('Has Won the Game')
                        isWin = True
                        title="""<span style="color:#66FF66">You are Victorious!</span>""".format()
                        message="Your Score - {}. AI Score - {}".format(score[0],score[1])
                    else:
                        title="""<span style="color:#FF4444">You were defeated!</span>""".format()
                        message="Your Score - {}. AI Score - {}".format(score[0],score[1])

                    # Check if the contestant is playing for their current level
                    checkForLevelQuery="""
                    SELECT `currentLevel` FROM `teams` WHERE `id` = {0}
                    """
                    queryLevel = engine.execute(checkForLevelQuery.format(teamId))
                    for row in queryLevel:
                        currentLevel = row[0]

                    if currentLevel == currentSubmissionLevel:
                        print('Is Contesting for his current Level')
                        isHigherLevel = True
                    else:
                        print('Is Contesting for previous level')

                    if isWin:
                        # Check if the score that the player obtained
                        # is higher than their previous score
                        checkForHighScoreQuery="""
                        SELECT `score` FROM `teams` WHERE `id` = {0}
                        """
                        queryScores = engine.execute(checkForHighScoreQuery.format(teamId))
                        obtainedScore = -float('inf')
                        for row in queryScores:
                            obtainedScore = row[0]
                        if scoreDifference > obtainedScore:
                            print('Has obtained a new high score')
                            isNewHighScore = True

                    # Update the scores, if the simulation succeeded and the player won
                    if status is "COMPLETED":
                        if isWin and isHigherLevel:
                            updateScoresQuery="""
                            UPDATE `teams` SET `score` = {0} WHERE `id` = {1}
                            """
                            engine.execute(updateScoresQuery.format(scoreDifference,teamId))
                            updateLevelQuery="""
                            UPDATE `teams` SET `currentLevel` = {0} WHERE `id` = {1}
                            """
                            # If the current level the highest level, don't increment the level counter
                            if currentLevel == max_level:
                                currentLevel-=1
                            engine.execute(updateLevelQuery.format((currentLevel+1),teamId))
                        elif isWin and isNewHighScore:
                            updateScoresQuery="""
                            UPDATE `teams` SET `score` = {0} WHERE `id` = {1}
                            """
                            engine.execute(updateScoresQuery.format(scoreDifference,teamId))
                            pass
                        else:
                            pass
                    else:
                        # Send failed Notification
                        pass

                    # Add notifications for players
                    addNotifications(title,message,teamId);
                    print('Deleted job from queue')
                except Exception as err:
                    print('Catastrophic errors have occured!')
                    print("Error: {0}".format(err))
                    exc_type, exc_value, exc_traceback = sys.exc_info()
                    traceback.print_tb(exc_traceback, limit=1, file=sys.stdout)
                    # Set the submission status as failed
                    setFailedSubmissionStatusQuery="""
                    UPDATE `submissions` SET `status`='FAILED' WHERE `id`={}
                    """
                    engine.execute(setFailedSubmissionStatusQuery.format(queueList[i]['submissions_id']))
                    # Remove the bad job from the queue
                    removeBadJobFromQueueQuery="""
                    DELETE FROM `queue` WHERE `id` = {}
                    """
                    engine.execute(removeBadJobFromQueueQuery.format(queueList[i]['id']))
        time.sleep(5)

if __name__=='__main__':
    runSubmissions()
