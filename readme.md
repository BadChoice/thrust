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

## Events
It uses the standard Laravel events, but if you want to add some events only on Thrust pages you can do it like this in you `AppServiceProvider`
```
    Thrust::serving(function () {
        User::observe(UserObserver::class);
    });
```
This way the observers will only be registered when using a thrust function
