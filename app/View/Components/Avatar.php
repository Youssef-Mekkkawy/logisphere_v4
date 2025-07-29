<?php

// File: app/View/Components/Avatar.php

namespace App\View\Components;

use Illuminate\View\Component;

class Avatar extends Component
{
    public $user;
    public $size;
    public $showStatus;
    public $clickable;
    public $tooltip;
    public $class;
    public $avatarUrl;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $user = null,
        $size = 40,
        $showStatus = false,
        $clickable = false,
        $tooltip = null,
        $class = ''
    ) {
        $this->user = $user ?? auth()->user();
        $this->size = $size;
        $this->showStatus = $showStatus;
        $this->clickable = $clickable;
        $this->tooltip = $tooltip;
        $this->class = $class;
        $this->avatarUrl = $this->generateAvatarUrl();
    }

    /**
     * Generate avatar URL based on user role and gender.
     */
    private function generateAvatarUrl()
    {
        if (!$this->user) {
            return $this->getDefaultAvatar();
        }

        $base = 'https://avataaars.io/';

        // Get user's primary role
        $userRole = $this->getUserRole();

        // Get avatar configuration based on role and gender
        $params = $this->getAvatarParams($userRole);

        // Always use Circle style
        $params['avatarStyle'] = 'Circle';

        return $base . '?' . http_build_query($params);
    }

    /**
     * Get user's primary role
     */
    private function getUserRole()
    {
        if (!$this->user) {
            return 'guest';
        }

        // Check if admin
        if ($this->user->isAdmin()) {
            return 'admin';
        }

        // Get first role or default to employee
        $firstRole = $this->user->roles->first();
        if ($firstRole) {
            return strtolower($firstRole->slug);
        }

        return 'employee';
    }

    /**
     * Get avatar parameters based on role and gender
     */
    private function getAvatarParams($role)
    {
        $gender = $this->user->gender ?? 'male';

        // Base configurations by role
        $roleConfigs = [
            'admin' => [
                'male' => [
                    'topType' => 'ShortHairDreads01',
                    'clotheType' => 'BlazerShirt',
                    'eyeType' => 'Happy',
                    'mouthType' => 'Smile',
                    'skinColor' => 'Light',
                    'clotheColor' => 'Blue03',
                    'hairColor' => 'Auburn',
                ],
                'female' => [
                    'topType' => 'LongHairBigHair',
                    'clotheType' => 'BlazerSweater',
                    'eyeType' => 'Happy',
                    'mouthType' => 'Smile',
                    'skinColor' => 'Light',
                    'clotheColor' => 'PastelRed',
                    'hairColor' => 'Brown',
                ],
                'other' => [
                    'topType' => 'ShortHairShortCurly',
                    'clotheType' => 'BlazerShirt',
                    'eyeType' => 'Happy',
                    'mouthType' => 'Smile',
                    'skinColor' => 'Tanned',
                    'clotheColor' => 'Gray01',
                    'hairColor' => 'Black',
                ]
            ],
            'manager' => [
                'male' => [
                    'topType' => 'ShortHairShortFlat',
                    'clotheType' => 'BlazerSweater',
                    'eyeType' => 'Default',
                    'mouthType' => 'Smile',
                    'skinColor' => 'Tanned',
                    'clotheColor' => 'Blue02',
                    'hairColor' => 'BrownDark',
                ],
                'female' => [
                    'topType' => 'LongHairStraight',
                    'clotheType' => 'BlazerSweater',
                    'eyeType' => 'Default',
                    'mouthType' => 'Smile',
                    'skinColor' => 'Tanned',
                    'clotheColor' => 'PastelBlue',
                    'hairColor' => 'Blonde',
                ],
                'other' => [
                    'topType' => 'ShortHairShortWaved',
                    'clotheType' => 'BlazerSweater',
                    'eyeType' => 'Default',
                    'mouthType' => 'Smile',
                    'skinColor' => 'Brown',
                    'clotheColor' => 'Gray02',
                    'hairColor' => 'Red',
                ]
            ],
            'employee' => [
                'male' => [
                    'topType' => 'ShortHairShortRound',
                    'clotheType' => 'Hoodie',
                    'eyeType' => 'Default',
                    'mouthType' => 'Default',
                    'skinColor' => 'Pale',
                    'clotheColor' => 'Blue01',
                    'hairColor' => 'Brown',
                ],
                'female' => [
                    'topType' => 'LongHairStraight',
                    'clotheType' => 'Hoodie',
                    'eyeType' => 'Default',
                    'mouthType' => 'Default',
                    'skinColor' => 'Pale',
                    'clotheColor' => 'PastelGreen',
                    'hairColor' => 'Auburn',
                ],
                'other' => [
                    'topType' => 'ShortHairShaggyMullet',
                    'clotheType' => 'Hoodie',
                    'eyeType' => 'Default',
                    'mouthType' => 'Default',
                    'skinColor' => 'Yellow',
                    'clotheColor' => 'Gray01',
                    'hairColor' => 'Platinum',
                ]
            ],
            'user' => [
                'male' => [
                    'topType' => 'ShortHairShortFlat',
                    'clotheType' => 'ShirtCrewNeck',
                    'eyeType' => 'Default',
                    'mouthType' => 'Default',
                    'skinColor' => 'Light',
                    'clotheColor' => 'White',
                    'hairColor' => 'Black',
                ],
                'female' => [
                    'topType' => 'LongHairCurly',
                    'clotheType' => 'ShirtCrewNeck',
                    'eyeType' => 'Default',
                    'mouthType' => 'Default',
                    'skinColor' => 'Light',
                    'clotheColor' => 'PastelYellow',
                    'hairColor' => 'Red',
                ],
                'other' => [
                    'topType' => 'ShortHairShortCurly',
                    'clotheType' => 'ShirtCrewNeck',
                    'eyeType' => 'Default',
                    'mouthType' => 'Default',
                    'skinColor' => 'DarkBrown',
                    'clotheColor' => 'Blue01',
                    'hairColor' => 'SilverGray',
                ]
            ],
            'guest' => [
                'male' => [
                    'topType' => 'ShortHairShaggyMullet',
                    'clotheType' => 'Overall',
                    'eyeType' => 'Default',
                    'mouthType' => 'Default',
                    'skinColor' => 'Light',
                    'clotheColor' => 'Blue02',
                    'hairColor' => 'Brown',
                ],
                'female' => [
                    'topType' => 'LongHairBun',
                    'clotheType' => 'Overall',
                    'eyeType' => 'Default',
                    'mouthType' => 'Default',
                    'skinColor' => 'Light',
                    'clotheColor' => 'PastelOrange',
                    'hairColor' => 'Blonde',
                ],
                'other' => [
                    'topType' => 'Eyepatch',
                    'clotheType' => 'Overall',
                    'eyeType' => 'Default',
                    'mouthType' => 'Default',
                    'skinColor' => 'Tanned',
                    'clotheColor' => 'Gray01',
                    'hairColor' => 'Red',
                ]
            ],
        ];

        // Get config for role and gender, fallback to guest male
        $roleConfig = $roleConfigs[$role] ?? $roleConfigs['guest'];
        return $roleConfig[$gender] ?? $roleConfig['male'] ?? $roleConfigs['guest']['male'];
    }

    /**
     * Get default avatar for guests/null users
     */
    private function getDefaultAvatar()
    {
        $base = 'https://avataaars.io/';
        $params = [
            'avatarStyle' => 'Circle',
            'topType' => 'ShortHairShortFlat',
            'clotheType' => 'ShirtCrewNeck',
            'eyeType' => 'Default',
            'mouthType' => 'Default',
            'skinColor' => 'Light',
            'clotheColor' => 'Blue01',
            'hairColor' => 'Brown',
        ];

        return $base . '?' . http_build_query($params);
    }

    /**
     * Check if user is online (last activity within 5 minutes)
     */
    public function isUserOnline()
    {
        if (!$this->user || !$this->user->last_login) {
            return false;
        }

        return $this->user->last_login->diffInMinutes() < 5;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.avatar');
    }
}
