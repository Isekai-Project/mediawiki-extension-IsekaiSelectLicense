--[[
    用法：{{#invoke:CCLicenseBox|create|是否允许贡献|是否允许商业用途}}
    是否允许贡献：
        0: 禁止
        1: 允许
        2: 只要他人以相同方式共享
    是否允许商业用途：
        0：禁止
        1: 允许
]]

local p = {}

function getLicenseName(allowAdapt, allowCommercial)
	local licenseName = "cc-by"
	
	if allowCommercial == 0 then
		licenseName = licenseName .. "-nc"
	end
	
	-- 0：否 1：是 2：必须相同协议分享
	if allowAdapt == 0 then
		licenseName = licenseName .. "-nd"
	elseif allowAdapt == 2 then
		licenseName = licenseName .. "-sa"
	end
	
	return licenseName
end

function p.create(frame)
	local allowAdapt = tonumber(frame.args[1])
	local allowCommercial = tonumber(frame.args[2])
	local licenseName = getLicenseName(allowAdapt, allowCommercial)
	local alertType = 'info'
	local infoText = '本页面使用' .. frame:extensionTag('licenselink', licenseName, {target = '_blank'}) .. '进行授权。'
	
	if (allowAdapt == 1 or allowAdapt == 2) and allowCommercial == 1 then
		alertType = 'success'
	elseif allowAdapt == 1 or allowAdapt == 2 then
		alertType = 'info'
		infoText = infoText .. '请注意，本页面的授权协议与网站的默认授权协议不同。'
	else
		alertType = 'warning'
		infoText = infoText .. '请注意，本页面的授权协议与网站的默认授权协议不同。'
	end
	
	return frame:extensionTag('license', licenseName)
	.. frame:expandTemplate({ title = '提示框', args = { type = alertType, content = infoText } })
end

return p