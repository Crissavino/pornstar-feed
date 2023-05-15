# MindGeek Test

## Run the project
1. Open a terminal on the root of the project
2. Run `sh start_docker.sh` to build the docker image and start the containers
3. Open a browser and go to `http://localhost:80`

## Side notes
The project have scheduled tasks to:
- 1. Check every 3 hours and download, if necessary, the pornstar feed and cache the images
- 2. Retry all failed jobs every 15 minutes
- 3. Flush the failed jobs table every 1 hour
- 4. Clear the jobs table every 2 hours 

