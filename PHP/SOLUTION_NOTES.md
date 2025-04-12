# Solution Notes

## Initial Observations

1. There are 2 small classes ([Employee](src/Employee.php) and [XDate](src/XDate.php)) and 1 big class that holds most of the logic [BirthdayService](src/BirthdayService.php)
2. [XDate](src/XDate.php) wraps PHP's DateTime and looks fine for now
3. [Employee](src/Employee.php) seems to be an entity representing an employee with the data necessary for sending birthday emails
4. [BirthdayService](src/BirthdayService.php) holds both business logic regarding birthday emails sending and infrastructure tasks like reading files and using an email agent

## Architectural redesign

1. Regarding input of data, looks more natural just to have the date representing the day you want to use to check birthdays with
2. Another interesting approach would be to have a Calendar collaborator able to return the today date
3. To keep passing a filename or the remote email agent's host and port seems incorrect regarding the kata goals

### Secondary adapters

Here are more work that can be done

#### Employee retrieving

1. Prevent [BirthdayService](src/BirthdayService.php) from managing file resources and reading file contents directly
   1. Inject an employee repository instead
   2. Create a file employee repository adapter
2. One important decision here is to decide whether to return just the employees whose birthday is today or not
   1. As filtering could get more tricky because of the leap years and so, I prefer to just retrieve all employees

#### Email sending

1. Prevent [BirthdayService](src/BirthdayService.php) from opening sockets or manage access to remote services on its own
   1. Inject a notification sender instead
   2. Create an email notification sender adapter
   3. We may discuss here about:
      1. naming the port email sender vs notification sender vs birthday note sender and implications of each of them
      2. I choose to name it notification sender

##### Email composition

1. Here I might use a view model composition approach

## Additional notes

### Auto imposed additional constraints

1. CQRS approach
2. Tracer code
