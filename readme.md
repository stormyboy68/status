# Features

This package is about assigning **status** to models in **Laravel**.\
The type of relationship between tables is many-to-many,\
but depending on your preference in using the command,\
it can be used like one-to-many morph relationships.

![][rel]

## main question why we require this package :
There is no need to define relationships anymore,
and it is enough to Add the necessary Trait 
<span style="margin:0px 5px;border-radius:10px;font-weight: bold;background: darkorange;padding:5px 10px;width: max-content;color:black;">
HasStatus</span> 
in the models to which the status is applied.
### Quick Start <br>

1. #### Installation:

   >     composer require asb/status

2. #### Run the migrations:<br>

   >     php artisan migrate

3. #### Add the necessary Trait to your model:<br>

   > ###### // The model requires this trait.
   >     use HasStatus;

4. #### Using:<br>

    + ###### Get all the Models that have this Status.<br>

   >     getModelsHave(string $status)

    + ###### Get all the Statuses of Model.

   >     getStatuses(Model $model)

    + ###### Check The model has this Status.

   >     hasStatuses(Model $model,string $status)

    + ###### it assigns a Status to the Model.

   >     assignStatus(Model $model,string $status)

    + ###### it adds a Status to the Model.

   >     addStatus(Model $model,string $status)

    + ###### it updates a Status from the Model and replace by new or a status that exists.

   >     updateStatus(Model $model,string $status,string $newStatus)

    + ###### it removes a status from the model.

   >     removeStatus(Model $model,string $status)

    + ###### it removes all statuses from the model.

   >     removeAllStatus(Model $model)

5. #### Using Status Model:

   > + ##### it Creates a Status.
   >       createStatusModel(string $status)

   > + ##### it gets all Status.
   >       getAllStatusModel(bool $onlyTrashed=false)

   > + ##### it gets a Status by title.
   >       getStatusModel(string $status)

   > + ##### it updates a Status by title and replace by new_title.
   >       updateStatusModel(string $status, string $update_status):

   > + ##### it removes a Status by title and removing the Status and from all Models.
   >       removeStatusModel(string $status) 



[rel]:./img/rel.png  "relationship image"
