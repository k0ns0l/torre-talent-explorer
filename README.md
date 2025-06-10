# Torre Talent Explorer

A lightweight tool to search and explore talent and organizations using Torre.ai APIs.

## Features

- [ ] Search people and organizations based on various filters (**MVP**) 
- [x] Retrieve detailed genome profiles
- [x] Favorite and manage profiles
- [x] Simple integration with Torre’s talent and job search APIs  
   Useful for talent sourcing, job market research, and analytics

## Usage

- **Search Entities:**  
  POST to `/api/entities/_searchStream` with JSON filters to find people or organizations.  
- **Get Genome Profile:**  
  GET `/api/genome/bios/{username}` to retrieve detailed user profiles.  
- Refer to official API docs for more endpoints:  
  - [People Search Docs](https://arda.torre.co/webjars/swagger-ui/index.html)  
  - [Job Search Docs](https://search.torre.co/webjars/swagger-ui/index.html)

## Assignment/vibe/motivation 

This project is based on the technical test assignment available at :: [Torre Technical Test](https://torrelabs.notion.site/Torre-Engineering-technical-test-v-2-1-20a0dc7a971a80d79b37dc9f09f9f477#20a0dc7a971a814297e6f2b20acac89a)


This is a work in production. Feel free to contribute, or suggest improvements. :)