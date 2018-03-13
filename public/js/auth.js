$(document).ready(function() {
    if ($('#entityeditform').length > 0)
    {
        $('#entityName').on('input', function() {
            var searchKeyword = $(this).val();
            if (searchKeyword.length >= 3) {

                $.get('/api/internal/searchEntities', { name: searchKeyword }, function(data) {
                    $('#fieldAddEntity').text("");
                    $.each(data, function() {
                        $('#fieldAddEntity').append('<div style="margin-top:5px;"><img style="padding-right:10px;" src="https://image.eveonline.com/'+this.type+'/'+this.id+'_32.png"/>['+this.ticker+'] '+ this.name + ' <button type="button" class="btn btn-primary btn-sm" onclick="group_addEntityToGroup('+this.id+',&quot;'+this.type+'&quot;)">Add</button></div>');
                    });
                }, "json");
            }
        });
    }
    if ($('#memberaddform').length > 0)
    {
        $('#memberName').on('input', function() {
            var searchKeyword = $(this).val();
            if (searchKeyword.length >= 3) {

                $.get('/api/internal/searchUsers', { name: searchKeyword }, function(data) {
                    $('#fieldAddMember').text("");
                    $.each(data, function() {
                        $('#fieldAddMember').append('<div style="margin-top:5px;"><img style="padding-right:10px;" src="https://image.eveonline.com/character/'+this.id+'_32.jpg"/>['+this.ticker+'] '+ this.name + ' <button type="button" class="btn btn-primary btn-sm" onclick="group_addMemberToGroup('+this.id+')">Add</button></div>');
                    });
                }, "json");
            }
        });
    }
});

function group_addEntityToGroup(id,type)
{
    $('#entityeditform input[name="id"]').val(id);
    $('#entityeditform input[name="type"]').val(type);
    $('#entityeditform').submit();
}
function group_addMemberToGroup(id)
{
    $('#memberaddform input[name="id"]').val(id);
    $('#memberaddform').submit();
}
function group_removeMember(id)
{
    $('#memberremoveform input[name="id"]').val(id);
    $('#memberremoveform').submit();
}
