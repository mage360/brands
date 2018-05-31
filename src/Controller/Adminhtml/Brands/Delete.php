<?php
declare(strict_types=1);
/**
 * Mage360_Brands extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category  Mage360
 * @package   Mage360_Brands
 * @copyright 2018 Mage360
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 * @author    Qaiser Bashir
 */
namespace Mage360\Brands\Controller\Adminhtml\Brands;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Mage360\Brands\Controller\Adminhtml\Brands;

class Delete extends Brands
{
    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('brand_id');
        if ($id) {
            try {
                $this->brandsRepository->deleteById($id);
                $this->messageManager->addSuccessMessage(__('The Brand has been deleted.'));
                $resultRedirect->setPath('brands/*/');
                return $resultRedirect;
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('The Brand no longer exists.'));
                return $resultRedirect->setPath('brands/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('brands/brands/edit', ['brand_id' => $id]);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('There was a problem deleting the Brand'));
                return $resultRedirect->setPath('brands/brands/edit', ['brand_id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a Brand to delete.'));
        $resultRedirect->setPath('brands/*/');
        return $resultRedirect;
    }
}
