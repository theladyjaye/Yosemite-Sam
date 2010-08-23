peeq.prototype.utils = 
{
	template: 
	{
		index: function(item, array)
		{
			return $.inArray(item, array) + 1;
		},
		percentage: function(dividend, divisor)
		{
			return (divisor > 0) ? parseInt((dividend / divisor) * 100, 10) : 100;
		},
		truncate: function(str, maxChars) 
		{
			var bits, i;
			if ("string" !== typeof str) 
			{
				return '';
			}
			bits = str.split('');
			if(bits.length > maxChars) 
			{
				for (i = bits.length - 1; i > -1; --i) 
				{
					if (i > maxChars) 
					{
						bits.length = i;
					}
					else if (' ' === bits[i]) 
					{
						bits.length = i;
						break;
					}
				}
				bits.push('...');
			}
			return bits.join(''); 
		},
		elapsed_time: function(created_at)
		{
			var now 	= new Date(),
				created = new Date();
				
			created.setFullYear(created_at.substr(0, 4));
			created.setMonth(created_at.substr(5, 2) - 1);
			created.setDate(created_at.substr(8, 2));
			created.setHours(created_at.substr(11, 2));
			created.setMinutes(created_at.substr(14, 2));
			created.setSeconds(created_at.substr(17, 2));			
			
			now.setHours(now.getHours() - 1);
			var age_in_seconds = (created.getTime() - now.getTime()) / 1000;

			var s = function(n) { 
				return n == 1 ? '' : 's' 
			};
			
		    if (age_in_seconds < 0) 
			{
		        return 'just now';
		    }
		    if (age_in_seconds < 60) 
			{
		        var n = age_in_seconds;
		        return n + ' second' + s(n) + ' ago';
		    }
		    if (age_in_seconds < 60 * 60) 
			{
		        var n = Math.floor(age_in_seconds/60);
		        return n + ' minute' + s(n) + ' ago';
		    }
		    if (age_in_seconds < 60 * 60 * 24) 
			{
		        var n = Math.floor(age_in_seconds/60/60);
		        return n + ' hour' + s(n) + ' ago';
		    }
		    if (age_in_seconds < 60 * 60 * 24 * 7)
		 	{
		        var n = Math.floor(age_in_seconds/60/60/24);
		        return n + ' day' + s(n) + ' ago';
		    }
		    if (age_in_seconds < 60 * 60 * 24 * 31) 
			{
		        var n = Math.floor(age_in_seconds/60/60/24/7);
		        return n + ' week' + s(n) + ' ago';
		    }
		    if (age_in_seconds < 60 * 60 * 24 * 365) 
			{
		        var n = Math.floor(age_in_seconds/60/60/24/31);
		        return n + ' month' + s(n) + ' ago';
		    }
		    var n = Math.floor(age_in_seconds/60/60/24/365);
		
		    return n + ' year' + s(n) + ' ago';
		},
		get_completed_projects_count: function(data)
		{			
			var completed = 0;
			for(var i = 0, len = data.length, project; i < len; i++)
			{
				project = data[i];
				if(project.tasks.complete > 0 && project.tasks.complete == project.tasks.total)
				{
					completed++;
				}
			}
			
			return completed;
		}
	}
};