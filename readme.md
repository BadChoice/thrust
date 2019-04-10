#Thrust

## Fields
- Validation rules
- Show in Index / Edit
- Visibility 

### Basic Types
- Check
- Check Switch
- Color
- Currency
- Date
- Datetime
- Email
- Hidden
- Integer
- Link
- Password
- Percentage
- Range
- Select
- Text
- Textarea
- Time
- Url

### Powered up
- Panel
- Image
- Place
- Gravatar
- ParentId

### Relationships
- HasMany
- HasOne
- Belongs to
- Belongs to many

### Resource
- Sortable
- Search
- Pagination
- Single resource
- Single resource search
- Automatic with
- Main Actions
- Row Actions
- Inline Edit
- Save and continue
- Table density
- Uses laravel policies for the crud related actions

## Events
It uses the standard Laravel events, but if you want to add some events only on Thrust pages you can do it like this in you `AppServiceProvider`
```
    Thrust::serving(function () {
        User::observe(UserObserver::class);
    });
```
This way the observers will only be registered when using a thrust function


 ## TODO:
[x] Migrate to select2 4.0   
[x] Make check fields to be toggable from the index   
[x] Pin validation not working (digits 4)   
[x] Update validation   
[x] Configurable route prefix   
[x] Make the service provider deffered as it doesn't need to be called in the API   
[x] Panel visibility by check and select   
[x] Make visibleWhen (for checkboxes, or type of printers... etc)   
[x] BelongsTo many ajax searchable   
[x] Prunable files, should be deleted when deleting resource   
[x] Update saveOrder function to use a thrust one instead of the retail/xef yet   
[x] Update saveOrder function to use the plural version of the resource name (the one we use on whole thrust) instead of the singular one   
[x] ThrustRelationshipController to use the $relationDisplayName instead of `name`   
[x] Employee, photo upload...   
[x] Add save and continue editing functionality   
[x] Inline editing!   
[x] Table density   
[x] Custom events to be different than the standard ones?   
[x] Quan sortable, afegir x defecte la main action save order   
[x] Make the resource found in app service provider recursive into thrust directory ?   
[x] Improved ResourceFilters\Search by words, fer-ho per el primer fields nomes?   
[x] Search through relationships   
[] Save image in thrust to use the same route as display   
[] Relationship rules, apply to field, and should apply to foreing_key when saving   
[] Use the search route into searcher, and pass the search parameter to query instead of a new url path parameter   
[] Delete validation   
[] Make sortable relationships (right now it uses the relationship name instead of the underling field)      
[] Add latlang to algolia places search?   
[] Search limitar-lo a 100 (configurable) sense pagination   
[] Make hideWhen and showWhen to make it work with Array fields    
