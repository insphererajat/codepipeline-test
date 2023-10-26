<?php
//Messages returned from exceptions & errors

return [
    
    'forbidden.invalid.request' => "Invalid request.",
    'forbidden.invalid.access' => "Invalid access.",
    'forbidden.planner.access' => "Sorry! You do not have planner board permission. Please contact your administrator for help.",
    'forbidden.access.denied' => "Sorry! You do not have permission to perform this task. Please contact your administrator for help.",
    'social.not.allowed' => "Access denied. Sorry! {social} is not allowed in your plan.",
    'module.not.allowed' => "Access denied. Sorry! {module} is not allowed in your plan.",
    'forbidden.cropimage.dimensions' => "Cropped area should be at least 100px in width & height.",
    'forbidden.invalid.args' => "Invalid arguements supplied.",
    'forbidden.missing.args' => "Required arguements are missing.",
    'forbidden.missing.googlekey' => "Channel Google Developer Key is not set or is invalid.",
    'forbidden.missing.vimeokeys' => "Unable to access Vimeo or keys/tokens are invalid or missing.",
    'forbidden.missing.gettykeys' => "To get access to Getty Images, speak to the <a href='mailto:{emailid}'><u>{title} team</u></a>.",
    'getty.server.error' => "System encountered an error while connectting to Getty server.",
    
    'server.error' => "Server encountered an error while processing your request.",
    'notfound.error' => "The requested {title} not found or does not exist.",
    
    'blog.required' => "Blog is mandatory to use this feature. Please contact your administrator.",
    
    'search.keyword.missing' => "Please provide search keyword or phrase, or it is empty.",
    'keyword.missing.error' => "Please enter a keyword.",
    'exists.error' => "{title} already exists.",
    'max.keyword.limit.reached' => "Keyword limit for your subscription plan has been reached. <a target='_blank' class='link-green' href='{upgradeLink}'>Click here</a> to upgrade your plan.",
    'max.keyword.limit.reached.no.upgrade' => "Keyword limit for your subscription plan has been reached.",
    'invalid.brightcove.credentials' => "Invalid or missing Brightcove API credentials.",
    'article.notfound' => "The article you are trying to access seems to be deleted or does not exist.",
    'article.edit.permission' => "You do not enough permissions to edit this article.",
    'blog.access.permission' => "You do not enough permissions to access this blog.",
    'article.under.review' => "Article is under review and has been locked.",
    'article.resend.error' => "Unable to resend article. Please try again later.",
    'rss.exists.error' => "Provided RSS URL already exists.",
    'aap.rss.exists.error' => "Provided AAP feed URL already exists.",
    'facebook.page.exists.error' => "Selected Facebook page already exists.",
    'linkedin.page.exists.error' => "Selected LinkedIn page already exists.",
    'buffer.profile.exists.error' => "Selected Instagram account (buffer) already exists.",
    'social.account.exists.error' => "This Social account already exists.",
    'youtube.channel.notfound' => "No Youtube channel found for provided keyword",
    'hashtag.handler.error' => "A keyword must start with either '@' or '#'.",
    
    'vimeo.connect.error' => "Vimeo is not connected for selected blog in Social OAuth.",
    'vimeo.token.error' => "Vimeo access token & client secret is either invalid or missing.",
    
    'cache.error' => "System encountered an error while trying to perform caching. Try again later.",
    'social.auth.error' => "Error in social authentication",
    'user.channel.permission' => "Sorry! You do not have enough permissions to access this channel.",
    'user.network.permission' => "Sorry! You do not have enough permissions to access this network.",
    'user.account.inactive' => "Sorry, your account is currently in in-active state.",
    
    'facebook.account.required' => "You must authenticate your Facebook account before system can pull in any facebook pages.",
    'pinterest.account.required' => "You must authenticate your Pinterest account before system can pull in any board.",
    'reset.password.error' => "Sorry, we are unable to reset password for email provided.",
    
    'category.select.error' => "Please select at least one category.",
    
    'article.swap.error' => "Unable to swap articles. Try again later.",
    'article.system.delete.error' => "System encountered an error during article deletion.",
    'article.social.delete.error' => "System encountered an error during social article deletion.",
    'article.pinunpin.error' => "System encountered an error during pin/unpin process of article.",
    'article.social.update.error' => "System encountered an error while updating the social article.",
    
    'blog.network.error' => "Invalid access. Blog do not belongs to this network.",
    'menu.child.error' => "Not allowed to add child element on same hierarchy.",
    'menu.label.empty.error' => "Label cannot be empty.",
    'menu.type.error' => "Provide a menu type.",
    
    'partner.upgrade.error' => "You must have a paid network to be able to purchase additional service packs. <a href='{upgradeLink}' class='link-green'>Click here</a> to upgrade.",
    'serviceplan.subscription.failed' => "Unable to complete Service Plan subscription successfully.",
    'serviceplan.unsubscription.failed' => "Unable to complete Service Plan un-subscription successfully.",
    'premium.feature.error' => "You have reached a premium feature. please upgrade your plan to access this feature at <a href='{manageLink}' class='link-blue' target='_blank'>{manageLink}</a>.",
    'feature.not.allowed' => "Feature you are trying to access is forbidden.",
    
    'team.author.exists' => "Author already exists in workflow.",
    'article.exists.teamgroup' => "Article(s) exists in this workflow group, so it can not be deleted.",
    'teamgroup.delete.error' => "Workflow group not found or system unable to delete it.",
    'user.network.error' => "User doesn't belong to current network.",
    'user.delete.error' => "Unable to delete user. Please try again later.",
    'user.ownaccount.delete.error' => "Sorry! You can not delete your own account.",
    
    'login.otp.error' => "Oops! OTP or Username is invalid or OTP has been expired.",
    'article.revno.error' => "It looks like either you have opened this article in other tab/browser or someone else is working on this article.",
    
    'event.enabled.error' => "It looks like 'Events' feature is not enabled in the active theme.",
    'contentEditor.enabled.error' => "It looks like 'Content Editor' feature is not enabled in the active theme.",
    'styling.enabled.error' => "It looks like 'Styling' feature is not enabled in the active theme.",
    
    //Planner Messages
    'planner.board.haslists' => "Sorry! You cannot delete a board which has list(s) assigned to it.",
    'planner.label.hastasks' => "Sorry! You cannot delete a label which has been assigned to a task.",
    'planner.list.hastasks' => "Sorry! You cannot delete a list which has tasks assigned to it.",
    'board.list.absent' => "No list found for this board. Please add a list before creating a task.",
    'no.article.found' => 'Its looks like the article you are trying to access is deleted or does not exist.',
    'task.no.articles' => "Sorry! No selected {title} found.",
    'already.assigned.articles' => "{title} already linked to this task.",
    'already.assigned.emailcontent' => "{title} already linked to this email content.",
    'already.assigned.relatedarticles' => "{title} already added to this article.",
    'task.already.link.to.article' => 'Sorry! Task already linked to article(s).',
  
    // Asset Messages
    'label.hasmedia' => "Sorry! You cannot delete a label which has been assigned to an asset.",
    'not.media.task.linking' => 'Sorry! No selected task found.',
    'already.list.to.board' => 'Sorry! You cannot delete a board which has list(s) assigned to it.',
    
    'social.post.published' => "You cannot update a published social post.",
    
    'not.social.task.linking' => 'Sorry! No selected task found.',
    
    'forbidden.blog.required' => "Sorry! You do not have permission to perform this task. Please contact your administrator for help.",
    
    'workflow.archived.delete' => "Workflow is archived and cannot be deleted.",
    
    'utmcampaign.url.missing' => "Please provide Campaign Url.",
    'utmcampaign.url.invalid' => "Please provide a valid Campaign Url. Eg: https://www.example.com/webpage",
    
    'invalid.time' => "Please provide valid time format. Eg. 2d 4h 30m",
    'invalid.currency' => "Please provide valid budget amount format Eg. $100.00"
];

